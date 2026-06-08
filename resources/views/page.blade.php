@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    @if (function_exists('is_cart') && is_cart())
      {{-- Cart page: bypass the page wrapper (container + page-header + article)
           AND skip the_content() — call the cart shortcode directly so Gutenberg
           paragraph-block wrappers around it don't produce invalid <p><div></div></p>
           nesting around our full-width sections. --}}
      {!! do_shortcode('[woocommerce_cart]') !!}
    @elseif (function_exists('is_checkout') && is_checkout() && ! is_order_received_page())
      {{-- Checkout (NU thank-you): la fel ca cart — ocolim wrapper-ul temei
           (container îngust + page-header „Finalizare comandă" + article) ca
           .checkout-page să fie full-bleed și fără titlu dublu, fix ca în mockup.
           Apelăm shortcode-ul direct ca să evităm wrapper-ele de paragraf Gutenberg. --}}
      {!! do_shortcode('[woocommerce_checkout]') !!}
    @elseif (function_exists('is_account_page') && is_account_page() && ! is_user_logged_in())
      {{-- Pagina de cont, vizitator NELOGAT (login/înregistrare): ocolim wrapper-ul
           temei (page-header „Cont" + container) ca pagina de autentificare să fie
           full-bleed, fix ca în mockup. Contul LOGAT păstrează wrapper-ul normal
           (dashboard-ul). --}}
      {!! do_shortcode('[woocommerce_my_account]') !!}
    @else
      <main class="single_page">
        <div class="container">
          @include('partials.page-header')
          <article @php(post_class('page-content'))>
            @includeFirst(['partials.content-page', 'partials.content'])
          </article>
        </div>
      </main>
    @endif
  @endwhile
@endsection
