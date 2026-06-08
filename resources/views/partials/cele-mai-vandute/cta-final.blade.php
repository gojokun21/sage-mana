{{-- Cele mai vândute — CTA final către catalog. --}}
<section class="cta-final">
  <div class="cta-final-inner">
    <h2>{{ __('Vezi catalogul', 'sage') }} <em>{{ __('complet.', 'sage') }}</em></h2>
    <p><strong>{{ __('20 de produse', 'sage') }}</strong> {{ __('și', 'sage') }} <strong>{{ __('11 pachete', 'sage') }}</strong> {{ __('în total. Aceste 5 sunt doar punctul de plecare — dacă nu te regăsești aici, restul catalogului are altă soluție pentru tine.', 'sage') }}</p>
    <a class="btn" href="{{ esc_url(get_post_type_archive_link('product') ?: home_url('/shop/')) }}">{{ __('Vezi toate suplimentele', 'sage') }}
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
    </a>
  </div>
</section>
