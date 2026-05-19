{{--
  Template Name: Landing Page — Newsletter Thank You
  Description: Thank-you page shown after a successful newsletter signup from
  the Newsletter LP (`template-newsletter-lp`). Lives on a dedicated URL so
  analytics / pixel platforms (TheMarketer, GTM, GA4 etc.) can fire a clean
  conversion event without depending on AJAX side-channels.

  Reuses the `.newsletter-lp` CSS scope so styling matches the LP 1:1 — the
  bundle is enqueued via App\page_bundles() (see app/setup.php).
--}}

@extends('layouts.landing')

@php
  $ty_brand_url   = home_url('/');
  $ty_shop_url    = function_exists('wc_get_page_id') && wc_get_page_id('shop') > 0
      ? get_permalink(wc_get_page_id('shop'))
      : home_url('/magazin/');
  // Find the page using the "Blog Template" (template-blog.blade.php) — that's
  // the actual blog index on this site, not the default post archive.
  $ty_blog_query = new \WP_Query([
      'post_type'              => 'page',
      'post_status'            => 'publish',
      'posts_per_page'         => 1,
      'no_found_rows'          => true,
      'update_post_meta_cache' => false,
      'update_post_term_cache' => false,
      'fields'                 => 'ids',
      'meta_query'             => [[
          'key'     => '_wp_page_template',
          'value'   => 'template-blog',
          'compare' => 'LIKE',
      ]],
  ]);
  $ty_blog_url = ! empty($ty_blog_query->posts)
      ? get_permalink($ty_blog_query->posts[0])
      : (get_post_type_archive_link('post') ?: home_url('/blog/'));
  $ty_privacy_url = get_privacy_policy_url() ?: '#';
  $ty_terms_url   = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('terms') : '#';
  $ty_logo_id     = get_theme_mod('custom_logo');
@endphp

@section('content')
<div class="newsletter-lp newsletter-lp-ty">

  {{-- ─── HEADER ─── --}}
  <header class="lp-header">
    <div class="lp-container lp-row">
      <a class="lp-brand" href="{{ $ty_brand_url }}" aria-label="{{ get_bloginfo('name') }}">
        @if ($ty_logo_id)
          {!! wp_get_attachment_image($ty_logo_id, 'full', false, ['alt' => get_bloginfo('name'), 'class' => 'lp-brand__img']) !!}
        @else
          <span class="lp-brand__text">{{ get_bloginfo('name') }}</span>
        @endif
      </a>
      <div class="lp-header__right">
        <span class="lp-secure">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 3l8 3v6c0 5-4 9-8 9s-8-4-8-9V6z"/><path d="M9 12l2 2 4-4"/></svg>
          <span>{{ __('Înscriere confirmată', 'sage') }}</span>
        </span>
        <a href="{{ $ty_shop_url }}">{{ __('Magazin →', 'sage') }}</a>
      </div>
    </div>
  </header>

  {{-- ─── THANK YOU HERO ─── --}}
  <section class="lp-hero lp-ty-hero">
    <div class="lp-container">
      <div class="lp-ty-card">

        <div class="lp-ty-check" aria-hidden="true">
          <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M20 7L9 18l-5-5"/></svg>
        </div>

        <div class="lp-hero__tag lp-ty-tag">
          <span class="lp-hero__pip">★</span>
          {{ __('Înscriere reușită · ghidul + codul −10%', 'sage') }}
        </div>

        <h1>
          {{ __('Mulțumim!', 'sage') }}<br>
          <span class="lp-serif">{{ __('Ghidul e pe drum.', 'sage') }}</span>
        </h1>

        <p class="lp-hero__lede lp-ty-lede">
          {{ __('Ți-am trimis pe email ghidul', 'sage') }}
          <b>„10 obiceiuri pentru o energie care durează”</b>
          {{ __('și codul tău personal de', 'sage') }} <b>10% REDUCERE</b>
          {{ __('la prima comandă. Verifică inboxul în următoarele minute.', 'sage') }}
        </p>

        {{-- Steps --}}
        <ol class="lp-ty-steps">
          <li>
            <span class="lp-ty-num">1</span>
            <div>
              <b>{{ __('Verifică emailul', 'sage') }}</b>
              <span>{{ __('Caută mesajul nostru în Inbox. Dacă nu apare în 5 minute, verifică folderul „Promoții” sau „Spam”.', 'sage') }}</span>
            </div>
          </li>
          <li>
            <span class="lp-ty-num">2</span>
            <div>
              <b>{{ __('Descarcă ghidul', 'sage') }}</b>
              <span>{{ __('PDF de 20 de pagini, format mobile-friendly. Începe cu obiceiul nr. 1, e cel mai simplu de aplicat.', 'sage') }}</span>
            </div>
          </li>
          <li>
            <span class="lp-ty-num">3</span>
            <div>
              <b>{{ __('Folosește codul −10%', 'sage') }}</b>
              <span>{{ __('Codul tău personal e valabil 30 de zile la prima comandă, fără valoare minimă.', 'sage') }}</span>
            </div>
          </li>
        </ol>

        <div class="lp-ty-ctas">
          <a href="{{ $ty_shop_url }}" class="lp-btn lp-btn--primary lp-btn--lg">
            {{ __('Mergi în magazin', 'sage') }}
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </a>
          <a href="{{ $ty_blog_url }}" class="lp-btn lp-btn--ghost lp-btn--lg">
            {{ __('Citește articolele noastre', 'sage') }}
          </a>
        </div>

        <div class="lp-ty-trust">
          <span class="lp-secure-row__it">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 8l9 6 9-6"/><rect x="3" y="6" width="18" height="14" rx="2"/></svg>
            {{ __('Email trimis în mai puțin de 1 minut', 'sage') }}
          </span>
          <span class="lp-secure-row__it">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M9 12l2 2 4-4"/></svg>
            {{ __('Te poți dezabona oricând, dintr-un click', 'sage') }}
          </span>
        </div>

      </div>
    </div>
  </section>

  {{-- ─── FOOTER ─── --}}
  <footer class="lp-footer">
    <div class="lp-container lp-row">
      <div>&copy; {{ date('Y') }} {{ get_bloginfo('name') }} · {{ __('Sat Poșta, Comuna Remetea Chioarului nr. 41, România', 'sage') }}</div>
      <div class="lp-footer__links">
        <a href="{{ $ty_privacy_url }}">{{ __('Politica de confidențialitate', 'sage') }}</a>
        <a href="{{ $ty_terms_url }}">{{ __('Termeni & condiții', 'sage') }}</a>
        <a href="{{ home_url('/contact/') }}">{{ __('Contact', 'sage') }}</a>
      </div>
    </div>
  </footer>
</div>

{{-- Conversion signal for tracking. Pushes to dataLayer for GTM and fires a
     `natura:newsletter_signup` CustomEvent that TheMarketer / other scripts
     can subscribe to. Kept inline so it ships even if the JS bundle is slow. --}}
<script>
(function () {
  var payload = { event: 'newsletter_signup', source: 'newsletter_lp' };
  window.dataLayer = window.dataLayer || [];
  window.dataLayer.push(payload);
  try {
    window.dispatchEvent(new CustomEvent('natura:newsletter_signup', { detail: payload }));
  } catch (e) {}
})();
</script>
@endsection
