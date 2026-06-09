{{-- A single subscription card with management controls. $s = subscription view model. --}}
@php
  $isActive    = $s['status'] === 'active';
  $isPaused    = $s['status'] === 'paused';
  $isCancelled = $s['status'] === 'cancelled';
  $isFailed    = $s['status'] === 'payment_failed';
  $canModify   = $isActive || $isFailed;
@endphp

<div class="mn-subs-card" data-sub-id="{{ $s['id'] }}" data-status="{{ $s['status'] }}" data-within-cutoff="{{ $s['within_cutoff'] ? '1' : '0' }}">

  @if ($s['within_cutoff'] && $canModify)
    <div class="mn-subs-warning">
      <strong>{{ __('Următoarea livrare se procesează curând.', 'sage') }}</strong>
      {{ __('Modificările de dată, cantitate sau frecvență se aplică livrării de după aceasta.', 'sage') }}
    </div>
  @endif

  @if ($isFailed)
    <div class="mn-subs-warning">
      <strong>{{ __('Plata ultimei reînnoiri a eșuat.', 'sage') }}</strong>
      {{ __('Actualizează cardul pentru a relua abonamentul.', 'sage') }}
    </div>
  @endif

  <div class="mn-subs-card-head">
    <div class="mn-subs-thumb">{!! $s['product_thumb'] !!}</div>
    <div class="mn-subs-info">
      <h3>
        @if ($s['product_url'])<a href="{{ esc_url($s['product_url']) }}">{{ $s['product_name'] }}</a>@else{{ $s['product_name'] }}@endif
        <span class="mn-subs-chip mn-subs-chip--{{ $s['status'] }}">{{ $s['status_label'] }}</span>
      </h3>
      <div class="freq">{{ $s['interval_label'] }} · {{ sprintf(_n('%d bucată', '%d bucăți', $s['quantity'], 'sage'), $s['quantity']) }}</div>
    </div>
    <div class="mn-subs-price">
      <span class="now">{!! $s['price_html'] !!}</span>
      @if ($s['discount_pct'] > 0)
        <span class="vs"><s>{!! $s['base_html'] !!}</s> {{ sprintf(__('−%s%% abonament', 'sage'), rtrim(rtrim(number_format($s['discount_pct'], 2), '0'), '.')) }}</span>
      @endif
    </div>
  </div>

  @unless ($isCancelled)
    <div class="mn-subs-details">
      <div class="mn-subs-detail">
        <span class="lbl">{{ __('Următoarea livrare', 'sage') }}</span>
        <span class="val">
          @if ($s['next_label'])
            {{ $s['next_label'] }}
            <small>
              @if ($isPaused)
                {{ __('în pauză', 'sage') }}
              @elseif ($s['days_until'] !== null)
                {{ sprintf(_n('peste %d zi', 'peste %d zile', max(0, (int) $s['days_until']), 'sage'), max(0, (int) $s['days_until'])) }}
              @endif
            </small>
          @else
            —
          @endif
        </span>
      </div>
      <div class="mn-subs-detail">
        <span class="lbl">{{ __('Adresă', 'sage') }}</span>
        <span class="val">{{ $s['shipping']['city'] ?: '—' }}<small>{{ $s['shipping']['line'] }}</small></span>
      </div>
      <div class="mn-subs-detail">
        <span class="lbl">{{ __('Card de plată', 'sage') }}</span>
        <span class="val">{{ $s['card']['label'] ?: '—' }}<small>{{ $s['card']['exp'] ? sprintf(__('expiră %s', 'sage'), $s['card']['exp']) : '' }}</small></span>
      </div>
    </div>

    <div class="mn-subs-controls">
      @if ($isPaused)
        <div class="mn-subs-control-row">
          <button class="mn-subs-btn mn-subs-btn--primary" data-mn-subs-action="resume">{{ __('Reactivează acum', 'sage') }}</button>
        </div>
      @elseif ($canModify)
        <div class="mn-subs-control-row">
          <button class="mn-subs-btn" data-mn-subs-action="postpone" data-arg-days="7">{{ __('Amână 7 zile', 'sage') }}</button>
          <button class="mn-subs-btn" data-mn-subs-action="skip">{{ __('Omite livrarea', 'sage') }}</button>

          <span class="mn-subs-inline" data-control-group>
            <input type="date" class="mn-subs-input" data-field="date" style="width:auto" aria-label="{{ esc_attr__('Data nouă', 'sage') }}" />
            <button class="mn-subs-btn" data-mn-subs-action="change_date">{{ __('Schimbă data', 'sage') }}</button>
          </span>

          <span class="mn-subs-inline" data-control-group>
            <select class="mn-subs-select" data-field="months" aria-label="{{ esc_attr__('Luni pauză', 'sage') }}">
              @for ($m = 1; $m <= $max_pause_months; $m++)
                <option value="{{ $m }}">{{ sprintf(_n('%d lună', '%d luni', $m, 'sage'), $m) }}</option>
              @endfor
            </select>
            <button class="mn-subs-btn" data-mn-subs-action="pause">{{ __('Pauză', 'sage') }}</button>
          </span>

          <span class="mn-subs-inline" data-control-group>
            <select class="mn-subs-select" data-field="interval" aria-label="{{ esc_attr__('Frecvență', 'sage') }}">
              @foreach ($allowed_intervals as $days => $label)
                <option value="{{ $days }}" @selected((int) $days === (int) $s['interval_days'])>{{ $label }}</option>
              @endforeach
            </select>
            <button class="mn-subs-btn" data-mn-subs-action="change_frequency">{{ __('Schimbă frecvența', 'sage') }}</button>
          </span>
        </div>

        <div class="mn-subs-small-links">
          <span class="mn-subs-inline" data-control-group>
            <input type="number" min="1" class="mn-subs-input" data-field="quantity" value="{{ $s['quantity'] }}" aria-label="{{ esc_attr__('Cantitate', 'sage') }}" />
            <button data-mn-subs-action="change_qty">{{ __('Schimbă cantitatea', 'sage') }}</button>
          </span>
          <span class="sep">·</span>
          <button data-mn-subs-action="change_card">{{ __('Schimbă cardul', 'sage') }}</button>
          <span class="sep">·</span>
          <button class="cancel" data-mn-subs-action="open-cancel">{{ __('Renunță la abonament', 'sage') }}</button>
        </div>
      @endif
    </div>
  @endunless
</div>
