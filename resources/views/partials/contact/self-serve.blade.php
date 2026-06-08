{{-- Contact — self-service: poate găsești răspunsul mai rapid singur.
     Link-urile folosesc rute reale unde există (cont WooCommerce); restul sunt
     slug-uri plauzibile pe care le poți ajusta din temă. --}}
@php
  $orders_url = function_exists('wc_get_account_endpoint_url')
    ? wc_get_account_endpoint_url('orders')
    : home_url('/contul-meu/');
  $retur_url = home_url('/retur/');
  $quiz_url  = home_url('/test/');
  $plata_url = home_url('/recuperare-plata/');
@endphp
<section class="self-serve">
  <div class="ss-inner">
    <div class="ss-head">
      <div class="eyebrow">{{ __('Înainte să ne contactezi', 'sage') }}</div>
      <h2>{{ __('Poate găsești răspunsul', 'sage') }} <em>{{ __('mai rapid aici.', 'sage') }}</em></h2>
      <p>{!! wp_kses(__('Multe întrebări au răspuns deja în paginile noastre. Verifică întâi — poate <strong>economisești timpul de așteptare</strong>.', 'sage'), ['strong' => []]) !!}</p>
    </div>
    <div class="ss-grid">

      <a class="ss-card" href="{{ esc_url($orders_url) }}">
        <div class="ico"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="2" y="6" width="20" height="14" rx="2"/><path d="M16 2v4M8 2v4M2 10h20"/></svg></div>
        <h4>{{ __('Despre', 'sage') }} <em>{{ __('comanda mea.', 'sage') }}</em></h4>
        <p class="qs">{{ __('„Cum verific status comanda?” · „Când vine?” · „Pot anula?”', 'sage') }}</p>
        <span class="link">{{ __('Vezi „Comenzile mele”', 'sage') }}
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
        </span>
      </a>

      <a class="ss-card" href="{{ esc_url($retur_url) }}">
        <div class="ico"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg></div>
        <h4>{{ __('Vreau să', 'sage') }} <em>{{ __('returnez.', 'sage') }}</em></h4>
        <p class="qs">{{ __('„Cum returnez în 14 zile?” · „Cine plătește transportul?” · „Pot returna desigilat?”', 'sage') }}</p>
        <span class="link">{{ __('Vezi Politica de retur', 'sage') }}
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
        </span>
      </a>

      <a class="ss-card" href="{{ esc_url($quiz_url) }}">
        <div class="ico"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3M12 17h.01"/></svg></div>
        <h4>{{ __('Despre', 'sage') }} <em>{{ __('un produs.', 'sage') }}</em></h4>
        <p class="qs">{{ __('„Cum se ia?” · „Cura cât durează?” · „E sigur pentru copii sau în sarcină?”', 'sage') }}</p>
        <span class="link">{{ __('Fă testul de 60 sec', 'sage') }}
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
        </span>
      </a>

      <a class="ss-card" href="{{ esc_url($plata_url) }}">
        <div class="ico"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M2 10h20"/><path d="M6 14h2"/></svg></div>
        <h4>{{ __('Probleme cu', 'sage') }} <em>{{ __('plata.', 'sage') }}</em></h4>
        <p class="qs">{{ __('„Plata mea a eșuat — ce fac?” · „Pot plăti la livrare?” · „Card refuzat?”', 'sage') }}</p>
        <span class="link">{{ __('Vezi pagina recuperare plată', 'sage') }}
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
        </span>
      </a>

    </div>
  </div>
</section>
