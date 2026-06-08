{{--
  Free-shipping progress bar (orizontală, fundal verde-soft) per mockup.
  Vars:
    $missing  float  remaining amount until free shipping (0 = already qualifies)

  Wrapper-ul .free-shipping-box e păstrat pentru ca cart.js să poată face replaceWith.
--}}

@php
  $threshold = (float) \App\FREE_SHIPPING_MIN;
  $current = max(0, $threshold - (float) $missing);
  $pct = $threshold > 0 ? min(100, round(($current / $threshold) * 100)) : 100;
  $reached = $missing <= 0;
@endphp

<div class="free-shipping-box cart-ship-bar {{ $reached ? 'is-reached' : '' }}">
  <div class="cart-ship-bar__inner">
    <div class="cart-ship-bar__icon" aria-hidden="true">
      @if ($reached)
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
          <path d="M20 6 9 17l-5-5"/>
        </svg>
      @else
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="8" width="13" height="9" rx="1"/>
          <path d="M16 11h3l2 2v4h-5"/>
          <circle cx="7" cy="19" r="1.5"/>
          <circle cx="17" cy="19" r="1.5"/>
        </svg>
      @endif
    </div>

    <div class="cart-ship-bar__text">
      @if ($reached)
        {{ __('Beneficiezi de', 'sage') }}
        <strong>{{ __('transport gratuit', 'sage') }}</strong>
        ({{ __('peste', 'sage') }} {!! wc_price($threshold) !!}).
      @else
        {{ __('Mai ai', 'sage') }}
        <strong>{!! wc_price($missing) !!}</strong>
        {{ __('până la transport gratuit.', 'sage') }}
      @endif
    </div>

    <div class="cart-ship-bar__progress" aria-hidden="true">
      <div class="cart-ship-bar__fill" style="width: {{ $pct }}%"></div>
      @if ($reached)
        <div class="cart-ship-bar__marker"></div>
      @endif
    </div>

    <span class="cart-ship-bar__label">
      @if ($reached)
        {!! wc_price($threshold) !!} · {{ __('prag atins', 'sage') }}
      @else
        {{ $pct }}%
      @endif
    </span>
  </div>
</div>
