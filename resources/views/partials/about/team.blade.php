{{-- About — echipa. --}}
@php
  $team = [
    ['avatar' => 'F', 'role' => __('Fondator', 'sage'), 'h' => __('Viziunea brandului.', 'sage'), 'p' => __('Selecția produselor, parteneriatele cu producători, decizia finală privind ce intră în catalog.', 'sage')],
    ['avatar' => 'V', 'role' => __('Responsabil producție', 'sage'), 'h' => __('Vivens Genetica — <em>calitate & conformitate.</em>', 'sage'), 'p' => __('Standardele HACCP/ISO/GMP, notificările ANSVSA, controlul de calitate pe fiecare lot fabricat.', 'sage')],
    ['avatar' => 'S', 'role' => __('Suport clienți', 'sage'), 'h' => __('Îți răspunde personal.', 'sage'), 'p' => __('Pe WhatsApp și email în <strong>24h lucrătoare</strong>. Nu chatbot, nu scriptat — om real care înțelege contextul.', 'sage')],
  ];
@endphp
<section class="team">
  <div class="team-inner">
    <div class="team-head">
      <div class="eyebrow">{{ __('Echipa', 'sage') }}</div>
      <h2>{{ __('Cine', 'sage') }} <em>{{ __('suntem.', 'sage') }}</em></h2>
      <p>{!! wp_kses(__('Suntem o echipă mică — <strong>4 persoane</strong> dintr-un oraș mic. Nu suntem un grup multinațional. Asta înseamnă că <strong>răspundem personal</strong> la fiecare email, telefon și comandă.', 'sage'), ['strong' => []]) !!}</p>
    </div>
    <div class="team-grid">
      @foreach ($team as $member)
        <div class="team-card">
          <div class="avatar" aria-hidden="true">{{ $member['avatar'] }}</div>
          <span class="role-lbl">{{ $member['role'] }}</span>
          <h3>{!! wp_kses($member['h'], ['em' => []]) !!}</h3>
          <p>{!! wp_kses($member['p'], ['strong' => []]) !!}</p>
        </div>
      @endforeach
    </div>
    <div class="team-contact">
      <div class="copy">{!! wp_kses(__('Vrei să <strong>vorbești cu cineva?</strong> Suntem aici — fără filtre, fără hold message-uri.', 'sage'), ['strong' => []]) !!}</div>
      <div class="contacts">
        <a href="https://wa.me/40749492794" target="_blank" rel="noopener">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.5 14.4c-.3-.2-1.8-.9-2.1-1s-.5-.2-.7.2c-.2.3-.8 1-1 1.2-.2.2-.4.2-.7.1-.3-.2-1.3-.5-2.4-1.5-.9-.8-1.5-1.8-1.7-2.1z"/></svg>
          WhatsApp · 0749 492 794
        </a>
        <a href="mailto:suport@mananaturii.ro">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="m22 6-10 7L2 6"/></svg>
          suport@mananaturii.ro
        </a>
      </div>
    </div>
  </div>
</section>
