<?php
/**
 * PHPUnit bootstrap for the sage-nature theme.
 *
 * Tests run theme PHP (e.g. the custom mini-cart handler) with NO real
 * WordPress. `ABSPATH` is defined so guards pass; Brain\Monkey stubs WP/WC
 * functions and Mockery stands in for WC objects + the Acorn View facade
 * (illuminate/support is available via the theme's vendor autoloader).
 */

declare( strict_types=1 );

define( 'MN_THEME_TESTS', true );

if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

require_once dirname( __DIR__ ) . '/vendor/autoload.php';
