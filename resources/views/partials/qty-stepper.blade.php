{{--
  Qty stepper (canonical). Use this partial everywhere markup is hand-written
  (mini-cart, sticky bar, etc.). WC templates hit the same component via
  `resources/views/woocommerce/global/quantity-input.php`.

  Vars:
    $name         string  Input `name` attribute. Default: "quantity".
    $value        int     Current value. Default: 1.
    $min          int     Minimum. Default: 0.
    $max          int|''  Maximum (empty = unlimited). Default: ''.
    $step         int     Step. Default: 1.
    $size         string  '' | 'sm'. Default: ''.
    $input_class  string  Extra classes on the <input>.
    $input_attrs  array   Extra attributes on the <input> (e.g. data-foo).
    $readonly     bool    Makes the input readonly. Default: false.
    $disabled     bool    Disables the whole component. Default: false.
--}}

@php
  $name = $name ?? 'quantity';
  $value = $value ?? 1;
  $min = $min ?? 0;
  $max = $max ?? '';
  $step = $step ?? 1;
  $size = $size ?? '';
  $input_class = $input_class ?? '';
  $input_attrs = $input_attrs ?? [];
  $readonly = $readonly ?? true;
  $disabled = $disabled ?? false;
@endphp

<div class="qty-stepper{{ $size ? ' qty-stepper--' . $size : '' }}{{ $disabled ? ' is-disabled' : '' }}" data-qty-stepper>
  <button type="button"
          class="qty-stepper__btn"
          data-qty-ctrl="dec"
          aria-label="{{ esc_attr__('Scade cantitatea', 'sage') }}"
          @if ($disabled) disabled @endif>&minus;</button>

  <input type="number"
         class="qty-stepper__input qty {{ $input_class }}"
         name="{{ esc_attr($name) }}"
         value="{{ esc_attr($value) }}"
         min="{{ esc_attr($min) }}"
         @if ($max !== '' && $max !== null) max="{{ esc_attr($max) }}" @endif
         step="{{ esc_attr($step) }}"
         inputmode="numeric"
         autocomplete="off"
         @if ($readonly) readonly @endif
         @if ($disabled) disabled @endif
         @foreach ($input_attrs as $k => $v) {{ $k }}="{{ esc_attr($v) }}" @endforeach>

  <button type="button"
          class="qty-stepper__btn"
          data-qty-ctrl="inc"
          aria-label="{{ esc_attr__('Crește cantitatea', 'sage') }}"
          @if ($disabled) disabled @endif>+</button>
</div>
