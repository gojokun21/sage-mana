<?php

declare( strict_types=1 );

namespace App\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\View;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * Captures the first wp_send_json_* response. The handler wraps its body in
 * catch(\Throwable), so a throwing stub would be re-caught and re-sent — we
 * record the first (real) response and ignore any subsequent re-send.
 */
class Captured {
    public static ?array $resp = null;
    public static function set( bool $success, $data ): void {
        if ( self::$resp === null ) {
            self::$resp = [ 'success' => $success, 'data' => $data ];
        }
    }
    public static function reset(): void { self::$resp = null; }
}

/**
 * Fake WC cart capturing the operations the mini-cart handler performs.
 */
class FakeCart {
    public array $cart_contents = [];
    public $add_return = 'newkey';
    public array $added = [];
    public array $removed = [];
    public array $set = [];
    public bool $totals_calculated = false;
    public int $count = 0;
    public string $subtotal = '0,00 lei';
    public bool $empty = false;

    public function add_to_cart( $pid, $qty = 1, $vid = 0, $var = [] ) {
        $this->added[] = compact( 'pid', 'qty', 'vid', 'var' );
        return $this->add_return;
    }
    public function remove_cart_item( $key ) { $this->removed[] = $key; return true; }
    public function set_quantity( $key, $qty, $refresh = true ) { $this->set[] = compact( 'key', 'qty' ); return true; }
    public function calculate_totals() { $this->totals_calculated = true; }
    public function get_cart_contents_count() { return $this->count; }
    public function get_cart_subtotal() { return $this->subtotal; }
    public function is_empty() { return $this->empty; }
}

/**
 * @covers \App\mini_cart_handler
 * @covers \App\mini_cart_payload
 */
final class MiniCartTest extends TestCase {

    private static bool $loaded = false;

    protected function setUp(): void {
        parent::setUp();
        Monkey\setUp();
        Captured::reset();

        Monkey\Functions\stubTranslationFunctions();

        // Hooks fire at file load — make them no-ops, then load the file once.
        Functions\when( 'add_action' )->justReturn( true );
        Functions\when( 'add_filter' )->justReturn( true );

        Functions\when( 'check_ajax_referer' )->justReturn( true );
        Functions\when( 'wp_unslash' )->returnArg( 1 );
        Functions\when( 'sanitize_text_field' )->returnArg( 1 );
        Functions\when( 'sanitize_key' )->alias( fn( $v ) => strtolower( preg_replace( '/[^a-z0-9_\-]/i', '', (string) $v ) ) );
        Functions\when( 'absint' )->alias( fn( $v ) => abs( (int) $v ) );
        Functions\when( 'apply_filters' )->returnArg( 2 );
        Functions\when( 'wp_strip_all_tags' )->returnArg( 1 );
        Functions\when( 'wc_get_notices' )->justReturn( [] );
        Functions\when( 'wc_clear_notices' )->justReturn( null );

        // wp_send_json_* exit in WP; here they record the response and throw to
        // unwind. The handler's catch(\Throwable) re-sends, but Captured keeps
        // the first response.
        Functions\when( 'wp_send_json_success' )->alias( function ( $data = null ) {
            Captured::set( true, $data );
            throw new \RuntimeException( 'json_stop' );
        } );
        Functions\when( 'wp_send_json_error' )->alias( function ( $data = null ) {
            Captured::set( false, $data );
            throw new \RuntimeException( 'json_stop' );
        } );

        if ( ! self::$loaded ) {
            require dirname( __DIR__, 2 ) . '/app/mini-cart.php';
            self::$loaded = true;
        }

        $_POST = [];
    }

    protected function tearDown(): void {
        $_POST = [];
        Captured::reset();
        Facade::clearResolvedInstances();
        Monkey\tearDown();
        Mockery::close();
        parent::tearDown();
    }

    private function useCart( FakeCart $cart ): void {
        $wc = new class( $cart ) {
            public $cart;
            public function __construct( $c ) { $this->cart = $c; }
        };
        Functions\when( 'WC' )->justReturn( $wc );
    }

    private function fakeView( string $html = '<ul class="mini-cart"></ul>' ): void {
        $view = Mockery::mock();
        $view->shouldReceive( 'render' )->andReturn( $html );
        $factory = Mockery::mock();
        $factory->shouldReceive( 'make' )->andReturn( $view );
        View::swap( $factory );
    }

    /** Invoke the handler and return the captured [success, data] response. */
    private function runHandler(): array {
        Captured::reset();
        try {
            \App\mini_cart_handler();
        } catch ( \Throwable $e ) {
            // expected: stub unwinds via exception
        }
        $this->assertNotNull( Captured::$resp, 'Handler did not send a JSON response.' );
        return Captured::$resp;
    }

    /* ---------- payload ---------- */

    public function test_payload_shape(): void {
        $cart = new FakeCart();
        $cart->count = 3;
        $cart->subtotal = '312,80 lei';
        $this->useCart( $cart );
        $this->fakeView( '<ul>items</ul>' );

        $payload = \App\mini_cart_payload();

        $this->assertSame( '<ul>items</ul>', $payload['html'] );
        $this->assertSame( 3, $payload['count'] );
        $this->assertSame( '312,80 lei', $payload['subtotal'] );
        $this->assertFalse( $payload['is_empty'] );
    }

    /* ---------- add ---------- */

    public function test_add_success_returns_payload(): void {
        $cart = new FakeCart();
        $cart->count = 2;
        $this->useCart( $cart );
        $this->fakeView();

        $_POST = [ 'op' => 'add', 'product_id' => '294', 'qty' => '2' ];
        $resp = $this->runHandler();

        $this->assertTrue( $resp['success'] );
        $this->assertSame( 2, $resp['data']['count'] );
        $this->assertSame( [ 'pid' => 294, 'qty' => 2, 'vid' => 0, 'var' => [] ], $cart->added[0] );
        $this->assertTrue( $cart->totals_calculated );
    }

    public function test_add_rejects_missing_product(): void {
        $cart = new FakeCart();
        $this->useCart( $cart );

        $_POST = [ 'op' => 'add', 'product_id' => '0' ];
        $resp = $this->runHandler();

        $this->assertFalse( $resp['success'] );
        $this->assertSame( 'Produs invalid', $resp['data']['message'] );
        $this->assertEmpty( $cart->added );
    }

    public function test_add_qty_floored_to_one(): void {
        $cart = new FakeCart();
        $this->useCart( $cart );
        $this->fakeView();

        $_POST = [ 'op' => 'add', 'product_id' => '294', 'qty' => '0' ];
        $this->runHandler();

        $this->assertSame( 1, $cart->added[0]['qty'] );
    }

    public function test_add_failure_surfaces_notice(): void {
        $cart = new FakeCart();
        $cart->add_return = false;
        $this->useCart( $cart );
        Functions\when( 'wc_get_notices' )->justReturn( [ [ 'notice' => 'Stoc epuizat' ] ] );

        $_POST = [ 'op' => 'add', 'product_id' => '294', 'qty' => '1' ];
        $resp = $this->runHandler();

        $this->assertFalse( $resp['success'] );
        $this->assertSame( 'Stoc epuizat', $resp['data']['message'] );
    }

    /* ---------- remove ---------- */

    public function test_remove_existing_item(): void {
        $cart = new FakeCart();
        $cart->cart_contents = [ 'abc' => [ 'quantity' => 1 ] ];
        $this->useCart( $cart );
        $this->fakeView();

        $_POST = [ 'op' => 'remove', 'key' => 'abc' ];
        $resp = $this->runHandler();

        $this->assertTrue( $resp['success'] );
        $this->assertSame( [ 'abc' ], $cart->removed );
    }

    public function test_remove_ignores_unknown_key(): void {
        $cart = new FakeCart();
        $cart->cart_contents = [ 'abc' => [ 'quantity' => 1 ] ];
        $this->useCart( $cart );
        $this->fakeView();

        $_POST = [ 'op' => 'remove', 'key' => 'does-not-exist' ];
        $this->runHandler();

        $this->assertEmpty( $cart->removed );
    }

    /* ---------- update ---------- */

    public function test_update_zero_qty_removes_item(): void {
        $cart = new FakeCart();
        $cart->cart_contents = [ 'abc' => [ 'quantity' => 2, 'data' => null ] ];
        $this->useCart( $cart );
        $this->fakeView();

        $_POST = [ 'op' => 'update', 'key' => 'abc', 'qty' => '0' ];
        $this->runHandler();

        $this->assertSame( [ 'abc' ], $cart->removed );
        $this->assertEmpty( $cart->set );
    }

    public function test_update_positive_qty_sets_quantity(): void {
        $cart = new FakeCart();
        $cart->cart_contents = [ 'abc' => [ 'quantity' => 1, 'data' => null ] ];
        $this->useCart( $cart );
        $this->fakeView();

        $_POST = [ 'op' => 'update', 'key' => 'abc', 'qty' => '3' ];
        $resp = $this->runHandler();

        $this->assertTrue( $resp['success'] );
        $this->assertSame( [ [ 'key' => 'abc', 'qty' => 3 ] ], $cart->set );
        $this->assertEmpty( $cart->removed );
    }

    public function test_update_blocked_by_validation_filter(): void {
        $cart = new FakeCart();
        $cart->cart_contents = [ 'abc' => [ 'quantity' => 1, 'data' => null ] ];
        $this->useCart( $cart );
        Functions\when( 'apply_filters' )->justReturn( false ); // update validation blocks
        Functions\when( 'wc_get_notices' )->justReturn( [ [ 'notice' => 'Cantitate indisponibilă' ] ] );

        $_POST = [ 'op' => 'update', 'key' => 'abc', 'qty' => '5' ];
        $resp = $this->runHandler();

        $this->assertFalse( $resp['success'] );
        $this->assertSame( 'Cantitate indisponibilă', $resp['data']['message'] );
        $this->assertEmpty( $cart->set );
    }

    /* ---------- get / default ---------- */

    public function test_get_returns_payload_without_mutation(): void {
        $cart = new FakeCart();
        $cart->count = 1;
        $cart->subtotal = '156,40 lei';
        $this->useCart( $cart );
        $this->fakeView( '<ul>one</ul>' );

        $_POST = [ 'op' => 'get' ];
        $resp = $this->runHandler();

        $this->assertTrue( $resp['success'] );
        $this->assertSame( 1, $resp['data']['count'] );
        $this->assertSame( '156,40 lei', $resp['data']['subtotal'] );
        $this->assertEmpty( $cart->added );
        $this->assertEmpty( $cart->removed );
        $this->assertTrue( $cart->totals_calculated );
    }
}
