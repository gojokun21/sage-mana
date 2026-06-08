{{-- Obiectiv — micro-edu („De ce funcționează"). --}}
@php
  $eyebrow = \App\simptom_field('edu_eyebrow', __('De ce funcționează', 'sage'));
  $titlu = \App\simptom_field('edu_titlu', __('De ce funcționează <em>combinația asta</em>', 'sage'));
  $text = \App\simptom_field('edu_text', __('Oboseala cronică e rar dintr-o singură cauză. <strong>B-complex</strong> repune în funcțiune ciclul Krebs, de unde vine ATP-ul. <strong>Magneziul</strong> deblochează conversia. <strong>Adaptogenii</strong> (rhodiola, ginseng) reglează cortizolul. Când cele trei lucrează împreună, motorul celular repornește în 2–4 săptămâni.', 'sage'));
@endphp

<section class="edu">
  <div class="edu-inner">
    <div class="eyebrow">{{ $eyebrow }}</div>
    <h2>{!! wp_kses($titlu, ['em' => []]) !!}</h2>
    <p>{!! wp_kses($text, ['strong' => [], 'em' => []]) !!}</p>
  </div>
</section>
