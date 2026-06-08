{{-- Noutăți — formular „Anunță-mă” (VIZUAL deocamdată; checkboxurile derivă din tincturi). --}}
@php
  $eyebrow = \App\noutati_field('notify_eyebrow', __('Listă de lansare', 'sage'));
  $titlu = \App\noutati_field('notify_titlu', __('Anunță-mă <em>când sunt gata.</em>', 'sage'));
  $lede = \App\noutati_field('notify_lede', __('Îți scriem o singură dată per lansare. Fără newsletter, fără spam, fără reclame la alte produse.', 'sage'));
  $email_label = \App\noutati_field('notify_email_label', __('Adresa ta de email', 'sage'));
  $email_ph = \App\noutati_field('notify_email_placeholder', __('email@exemplu.ro', 'sage'));
  $which_label = \App\noutati_field('notify_which_label', __('Pentru care tincturi vrei să fii anunțat', 'sage'));
  $consent = \App\noutati_field('notify_consent', __('Înțeleg că primesc <strong>un singur email</strong> când fiecare tinctură este disponibilă, după care mă pot dezabona oricând cu un click.', 'sage'));
  $submit = \App\noutati_field('notify_submit', __('Înscrie-mă pe listă', 'sage'));
  $post_line = \App\noutati_field('notify_post_line', __('Nu vindem emailurile. Nu facem retargeting. Nu trimitem newslettere.', 'sage'));
@endphp
<section class="notify" id="notify">
  <div class="notify-inner">
    <div style="text-align:center"><span class="eyebrow-gold">{{ $eyebrow }}</span></div>
    <h2>{!! \App\noutati_kses($titlu) !!}</h2>
    @if ($lede)<p class="lede">{{ $lede }}</p>@endif
    <div class="notify-form">
      <label for="nt-email">{{ $email_label }}</label>
      <input type="email" id="nt-email" placeholder="{{ esc_attr($email_ph) }}" />

      @if ($tinctures)
        <div class="which">
          <label>{{ $which_label }}</label>
          <div class="which-list">
            @foreach ($tinctures as $i => $t)
              <div class="cb-row {{ $i === 0 ? 'checked' : '' }}">
                <div class="cb"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg></div>
                <div class="label-txt">{{ trim(wp_strip_all_tags($t['name'] ?? '')) }}@if (! empty($t['cat_chip']))<small>{{ $t['cat_chip'] }}</small>@endif</div>
              </div>
            @endforeach
          </div>
        </div>
      @endif

      <div class="consent checked">
        <div class="cb"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg></div>
        <span>{!! \App\noutati_kses($consent) !!}</span>
      </div>

      <button class="submit-btn" type="button" disabled aria-disabled="true">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        {{ $submit }}
      </button>
    </div>
    @if ($post_line)<p class="post-line">{{ $post_line }}</p>@endif
  </div>
</section>
