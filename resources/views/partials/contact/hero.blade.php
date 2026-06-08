{{-- Contact — hero. Text hardcodat conform mockup. --}}
<section class="c-hero">
  <div class="c-hero-inner">
    <div class="eyebrow">{{ __('Vorbim direct, fără filtre', 'sage') }}</div>
    <h1>{{ __('Hai să', 'sage') }} <em>{{ __('vorbim.', 'sage') }}</em></h1>
    <p class="sub">
      {!! wp_kses(
        __('Îți răspundem în <strong>maxim 24h</strong> pe email, în <strong>maxim 2h</strong> pe WhatsApp luni–vineri 9–17.', 'sage'),
        ['strong' => []]
      ) !!}
    </p>
  </div>
</section>
