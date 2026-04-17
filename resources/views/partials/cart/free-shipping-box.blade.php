{{--
  Vars:
    $missing  float  remaining amount until free shipping (0 = already qualifies)
--}}

<div class="free-shipping-box">
  <div class="shipping_box">
    <svg class="shipping_box__icon" width="36" height="36" viewBox="0 0 30 30" fill="#386356" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
      <path d="M20.291 20.58a1.611 1.611 0 1 0 0 3.222 1.611 1.611 0 0 0 0-3.222zm0 4.833a3.223 3.223 0 1 1 0-6.445 3.223 3.223 0 0 1 0 6.445zM9.28 20.58a1.611 1.611 0 1 0 0 3.222 1.611 1.611 0 0 0 0-3.222zm0 4.833a3.223 3.223 0 1 1 0-6.445 3.223 3.223 0 0 1 0 6.445z"/>
      <path d="M24.428 13.87l-2.195-4.365h-3.746V7.895h4.244a.806.806 0 0 1 .72.443l2.417 4.808-1.44.724zm-11.66 7.749h6.094v-1.611h-6.095v1.611zm-4.916 0H5.06a.806.806 0 0 1 0-1.611h2.793a.806.806 0 0 1 0 1.611z"/>
      <path d="M26.73 21.619h-3.033a.806.806 0 0 1 0-1.611h2.229v-4.155l-1.173-1.512h-6.266a.806.806 0 0 1-.806-.805v-5.64H5.06a.806.806 0 0 1 0-1.612h13.428c.445 0 .806.36.806.806v5.64h5.854c.25 0 .485.116.637.312l1.586 2.04c.11.14.169.315.169.495v5.236c0 .445-.36.806-.806.806zM7.8 17.527H4.092a.806.806 0 0 1 0-1.611H7.8a.806.806 0 0 1 0 1.611zm1.852-3.17H2.775a.806.806 0 0 1 0-1.611h6.876a.806.806 0 0 1 0 1.611zm-.686 3.274H2.091a.806.806 0 0 1 0-1.611h6.875a.806.806 0 0 1 0 1.611z"/>
    </svg>

    <div class="tillfree-shipping-text">
      @if ($missing > 0)
        {{ __('Doriți livrare gratuită? Vă lipsesc:', 'sage') }}
        <span>{!! wc_price($missing) !!}</span>
      @else
        <strong>{{ __('Felicitări!', 'sage') }}</strong> {{ __('Aveți livrare gratuită!', 'sage') }}
      @endif
    </div>
  </div>
</div>
