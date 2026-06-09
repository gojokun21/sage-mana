{{--
  My Account → Abonamente. Data from MN_Subs_Account::data() (mn-subscriptions plugin).
  Styling: plugin asset subscriptions-account.css; behaviour: subscriptions-account.js.
--}}
@php defined('ABSPATH') || exit; @endphp

<div class="mn-subs-account">

  <div class="mn-subs-head">
    <span class="eyebrow">{{ __('Cont · Abonamente', 'sage') }}</span>
    <h1>{!! wp_kses_post(__('Abonamentele tale, <em>sub control.</em>', 'sage')) !!}</h1>
    <p>{{ __('Tu decizi când și cum primești fiecare produs. Modifici, amâni sau oprești oricând — fără justificări, fără penalități.', 'sage') }}</p>
  </div>

  {{-- Value strip --}}
  <div class="mn-subs-value-strip">
    <div class="mn-subs-value-cell">
      <span class="big">{{ sprintf(_n('%d lună', '%d luni', $value_strip['months_with'], 'sage'), $value_strip['months_with']) }}</span>
      <span class="lbl">
        @if ($value_strip['first_order_label'])
          {{ sprintf(__('cu Mâna Naturii · din %s', 'sage'), $value_strip['first_order_label']) }}
        @else
          {{ __('cu Mâna Naturii', 'sage') }}
        @endif
      </span>
    </div>
    <div class="mn-subs-value-cell">
      <span class="big">{!! $value_strip['total_saved_html'] !!}</span>
      <span class="lbl">{{ __('economisiți prin abonament', 'sage') }}</span>
    </div>
    <div class="mn-subs-value-cell">
      <span class="big">{{ $value_strip['deliveries_done'] }} / {{ $value_strip['deliveries_total'] }}</span>
      <span class="lbl">{{ __('livrări procesate', 'sage') }}</span>
    </div>
  </div>

  {{-- Active / all subscriptions --}}
  @if (empty($subscriptions))
    <div class="mn-subs-card">
      <div class="mn-subs-empty">{{ __('Nu ai niciun abonament încă. Activează un abonament direct din pagina produsului.', 'sage') }}</div>
    </div>
  @else
    <div class="mn-subs-label-row">{{ sprintf(__('Abonamente · %d', 'sage'), count($subscriptions)) }}</div>
    @foreach ($subscriptions as $s)
      @include('partials.abonamente.subscription-card', ['s' => $s, 'allowed_intervals' => $allowed_intervals, 'max_pause_months' => $max_pause_months])
    @endforeach
  @endif

  {{-- Delivery history --}}
  <div class="mn-subs-label-row">{{ __('Istoric livrări', 'sage') }}</div>
  <div class="mn-subs-history">
    <div class="mn-subs-history-head">
      <h3>{{ __('Livrările tale', 'sage') }}</h3>
    </div>
    @if (empty($history))
      <div class="mn-subs-empty">{{ __('Nicio livrare încă.', 'sage') }}</div>
    @else
      <table class="mn-subs-table">
        <thead>
          <tr>
            <th>{{ __('Data', 'sage') }}</th>
            <th>{{ __('Produs', 'sage') }}</th>
            <th>{{ __('Suma', 'sage') }}</th>
            <th>{{ __('Status', 'sage') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($history as $h)
            <tr>
              <td>{{ $h['date'] }}</td>
              <td>
                @if ($h['order_url'])<a href="{{ esc_url($h['order_url']) }}">{{ $h['product'] }}</a>@else{{ $h['product'] }}@endif
              </td>
              <td class="amount">{!! $h['amount_html'] !!}</td>
              <td><span class="mn-subs-status-chip">{{ $h['status_label'] }}</span></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>

  {{-- Support --}}
  <div class="mn-subs-label-row">{{ __('Întrebări sau probleme?', 'sage') }}</div>
  <div class="mn-subs-support">
    <div class="mn-subs-support-card">
      <h5>{{ __('WhatsApp', 'sage') }}</h5>
      <a href="{{ esc_url($support['whatsapp']) }}">{{ __('Scrie pe WhatsApp →', 'sage') }}</a>
    </div>
    <div class="mn-subs-support-card">
      <h5>{{ __('Email', 'sage') }}</h5>
      <a href="mailto:{{ esc_attr($support['email']) }}">{{ esc_html($support['email']) }}</a>
    </div>
  </div>

  @include('partials.abonamente.cancel-modal')
</div>
