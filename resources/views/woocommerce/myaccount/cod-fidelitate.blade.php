{{--
  My Account — „Cod fidelitate" (program Constant).
  Date din pluginul mn-loyalty (MN_Loyalty_Account::data), randate în layout-ul
  din mockup `preferinte/Cont - Cod fidelitate.html`. Scope CSS: .loyalty-tab.
  JS (copy/share/FAQ) lazy din app.js pe `.loyalty-tab`.
--}}
@php
  defined('ABSPATH') || exit;
  $cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/');
  $share_subject = sprintf(__('%s lei reducere la Mâna Naturii', 'sage'), wp_strip_all_tags(wc_price($referral['points'] ?? 0)));
  $mail_subject = sprintf(__('Reducere la prima comandă Mâna Naturii', 'sage'));
@endphp

<div class="loyalty-tab">

  <div class="page-head">
    <div class="eyebrow">{{ __('Cont · Constant (programul de fidelitate)', 'sage') }}</div>
    <h1>{{ __('Constant: câștigi', 'sage') }} <em>{{ __('liber.', 'sage') }}</em></h1>
    <p>{{ __('Fără trucuri, fără expirare, fără condiții ascunse. Acumulezi puncte, le folosești când vrei.', 'sage') }}</p>
  </div>

  {{-- Puncte --}}
  <div class="points-card">
    <span class="label">{{ __('Soldul tău Constant', 'sage') }}</span>
    <h2 class="big">{{ number_format_i18n($balance) }}<span class="unit">{{ __('puncte', 'sage') }}</span></h2>
    <div class="equiv">
      <span>{{ __('Echivalent', 'sage') }}</span>
      <strong>{!! wc_price($balance_value) !!}</strong>
      <span class="eq-dot"></span>
      <span>{{ __('la următoarea comandă', 'sage') }}</span>
    </div>
    <p class="helper">
      <strong>{{ sprintf(__('1 punct = %s.', 'sage'), wp_strip_all_tags(wc_price($point_value))) }}</strong>
      {{ sprintf(__('Folosești minim %1$s puncte. Maxim %2$d%% din valoarea coșului poate fi plătit cu puncte.', 'sage'), number_format_i18n($min_redeem), $max_cart_pct) }}
    </p>
    @if ($balance >= $min_redeem)
      <a class="cta" href="{{ esc_url($cart_url) }}">{{ __('Folosește punctele la următoarea comandă', 'sage') }}</a>
    @endif
  </div>

  {{-- Tier --}}
  <div class="tier-card">
    <div class="tier-head">
      <div class="current">
        <div class="leaf-ico">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M12 2C12 2 8 6 8 12C8 16 10 19 12 22C14 19 16 16 16 12C16 6 12 2 12 2Z"/></svg>
        </div>
        <div class="ti-text">
          <span class="stage">{{ __('Tier actual', 'sage') }}</span>
          <h2>{{ $tier['label'] }}, <em>{{ $tier['sub'] }}.</em></h2>
        </div>
      </div>
      @if ($next_tier)
        <div class="to-next">
          <strong>{{ sprintf(__('%s puncte', 'sage'), number_format_i18n($points_to_next)) }}</strong>
          <span>{{ sprintf(__('până la tier %s', 'sage'), $next_tier['label']) }}</span>
        </div>
      @endif
    </div>

    <div>
      <div class="tier-progress">
        <div class="fill" style="width:{{ $progress_pct }}%"></div>
      </div>
      <div class="tier-progress-meta">
        <span><strong>{{ number_format_i18n($lifetime) }}</strong> {{ __('puncte acumulate', 'sage') }}</span>
        @if ($next_tier)
          <span>{{ __('până la', 'sage') }} <strong>{{ number_format_i18n($next_tier['min']) }}</strong></span>
        @endif
      </div>
    </div>

    <div class="tier-grid">
      @foreach ($tiers as $t)
        <div class="tier-pill {{ $t['key'] === $tier['key'] ? 'current' : 'locked' }}">
          <div class="tp-head">
            <div class="name-row">
              <div class="ti-leaf"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 2C12 2 8 6 8 12C8 16 10 19 12 22C14 19 16 16 16 12C16 6 12 2 12 2Z"/></svg></div>
              <div class="nm">{{ $t['label'] }}<small>{{ $t['sub'] }}</small></div>
            </div>
            @if ($t['key'] === $tier['key'])
              <span class="pin">{{ __('Aici ești', 'sage') }}</span>
            @endif
          </div>
          <div class="tp-range">
            @if (is_null($t['max']))
              {{ sprintf(__('%s+ puncte acumulate', 'sage'), number_format_i18n($t['min'])) }}
            @else
              {{ sprintf(__('%1$s–%2$s puncte acumulate', 'sage'), number_format_i18n($t['min']), number_format_i18n($t['max'])) }}
            @endif
          </div>
          <ul class="tp-benefits">
            @foreach ($t['perks'] as $perk)
              <li><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg><span>{{ $perk }}</span></li>
            @endforeach
          </ul>
        </div>
      @endforeach
    </div>
  </div>

  {{-- Cum câștigi --}}
  <div class="section-head">
    <h2>{{ __('Cum câștigi', 'sage') }} <em>{{ __('puncte.', 'sage') }}</em></h2>
    <p>{{ __('4 căi simple, fără cerințe ascunse.', 'sage') }}</p>
  </div>
  <div class="earn-grid">
    @foreach ($earn_rules as $rule)
      <div class="earn-card">
        <div class="et">{{ $rule['title'] }}</div>
        <div class="ep">{{ $rule['points'] }}<span class="pts">{{ $rule['unit'] }}</span></div>
        <div class="ed">{{ $rule['desc'] }}</div>
      </div>
    @endforeach
  </div>

  {{-- Referral --}}
  <div class="ref-card">
    <div class="ref-head">
      <div class="ico"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
      <h2>{{ __('Codul tău', 'sage') }} <em>{{ __('referral.', 'sage') }}</em></h2>
    </div>
    <p class="lead">{!! sprintf(__('Trimite codul prietenilor. <strong>Tu primești %1$s puncte</strong>, ei primesc <strong>%2$s puncte</strong> la prima lor comandă.', 'sage'), number_format_i18n((int) (\MN_Loyalty_Settings::ref_referrer())), number_format_i18n((int) (\MN_Loyalty_Settings::ref_referee()))) !!}</p>

    <div class="ref-code-box">
      <div class="col">
        <span class="lbl">{{ __('Cod personal', 'sage') }}</span>
        <span class="code">{{ $referral['code'] }}</span>
      </div>
      <button type="button" class="ref-copy-btn" data-mn-copy="{{ $referral['code'] }}" data-mn-toast="{{ esc_attr__('Cod copiat în clipboard', 'sage') }}">
        {{ __('Copiază cod', 'sage') }}
      </button>
    </div>

    <div class="ref-code-box">
      <div class="col">
        <span class="lbl">{{ __('Link complet de partajat', 'sage') }}</span>
        <span class="link-text">{{ $referral['link'] }}</span>
      </div>
      <button type="button" class="ref-copy-btn outline" data-mn-copy="{{ $referral['link'] }}" data-mn-toast="{{ esc_attr__('Link copiat în clipboard', 'sage') }}">
        {{ __('Copiază link', 'sage') }}
      </button>
    </div>

    <div class="ref-share-row">
      <a class="share-btn wa" target="_blank" rel="noopener" href="https://wa.me/?text={{ rawurlencode($referral['prefab']) }}">{{ __('WhatsApp', 'sage') }}</a>
      <a class="share-btn em" href="mailto:?subject={{ rawurlencode($mail_subject) }}&amp;body={{ rawurlencode($referral['prefab']) }}">{{ __('Email', 'sage') }}</a>
      <button type="button" class="share-btn cp" data-mn-copy="{{ $referral['prefab'] }}" data-mn-toast="{{ esc_attr__('Mesaj copiat în clipboard', 'sage') }}">{{ __('Copiază mesaj', 'sage') }}</button>
    </div>

    <div class="ref-stats">
      <div class="stat-block"><span class="n">{{ number_format_i18n($referral['friends']) }}</span><span class="t">{{ __('prieteni au folosit codul tău', 'sage') }}</span></div>
      <span class="stat-sep"></span>
      <div class="stat-block"><span class="n">{{ number_format_i18n($referral['points']) }}</span><span class="t">{{ __('puncte câștigate din referral', 'sage') }}</span></div>
    </div>
  </div>

  {{-- Istoric --}}
  <div class="history-card">
    <div class="history-head">
      <h2><span class="ico"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></span>{{ __('Istoric', 'sage') }} <em>{{ __('puncte.', 'sage') }}</em></h2>
      <span class="sub">{{ __('Ultimele 10 mișcări', 'sage') }}</span>
    </div>
    @if (! empty($history))
      <table class="history-table">
        <thead>
          <tr><th>{{ __('Data', 'sage') }}</th><th>{{ __('Descriere', 'sage') }}</th><th style="text-align:right">{{ __('Puncte', 'sage') }}</th><th style="text-align:right">{{ __('Sold', 'sage') }}</th></tr>
        </thead>
        <tbody>
          @foreach ($history as $row)
            <tr>
              <td class="ht-date">{{ $row['date'] }}</td>
              <td class="ht-desc">
                {{ $row['desc'] }}
                @if ($row['order_id'])
                  <a class="order-link" href="{{ esc_url(wc_get_endpoint_url('view-order', $row['order_id'], wc_get_page_permalink('myaccount'))) }}">#{{ $row['order_id'] }}</a>
                @endif
              </td>
              <td class="ht-pts {{ $row['sign'] }}">{{ $row['points_s'] }}</td>
              <td class="ht-bal">{{ $row['balance'] }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @else
      <p class="history-empty">{{ __('Încă nu ai mișcări de puncte. Prima ta comandă finalizată îți aduce primele puncte.', 'sage') }}</p>
    @endif
  </div>

  {{-- FAQ --}}
  <div class="faq-card">
    <div class="fh">
      <div class="ico"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg></div>
      <h2>{{ __('Câteva întrebări', 'sage') }} <em>{{ __('frecvente.', 'sage') }}</em></h2>
    </div>
    @foreach ($faq as $item)
      <div class="faq-item" data-mn-faq>
        <div class="faq-q">{{ $item['q'] }}<span class="qchev"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg></span></div>
        <div class="faq-a">{{ $item['a'] }}</div>
      </div>
    @endforeach
  </div>

</div>
