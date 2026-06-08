{{-- Retur — 3 scenarii: sigilat / defect / desigilat. --}}
<section class="when-section">
  <div class="when-inner">
    <div class="when-head">
      <div class="eyebrow">{{ __('Trei scenarii clare', 'sage') }}</div>
      <h2>{{ __('În ce cazuri', 'sage') }} <em>{{ __('se poate returna.', 'sage') }}</em></h2>
    </div>
    <div class="when-grid">

      {{-- Sigilat în 14 zile --}}
      <div class="case-card ok">
        <div class="stamp">
          <span class="ico"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg></span>
          {{ __('Da, se returnează', 'sage') }}
        </div>
        <h3>{!! wp_kses(__('Produs <em>sigilat</em>, în 14 zile.', 'sage'), ['em' => []]) !!}</h3>
        <ul>
          <li>{!! wp_kses(__('Ambalaj original <strong>sigilat și integru</strong>', 'sage'), ['strong' => []]) !!}</li>
          <li>{{ __('Maxim 14 zile calendaristice de la primire', 'sage') }}</li>
          <li>{{ __('Cu factură sau bon fiscal anexat', 'sage') }}</li>
        </ul>
        <div class="meta-row">
          <span class="k">{{ __('Cost transport retur', 'sage') }}</span>
          <span class="v">{!! wp_kses(__('Plătit de <strong>consumator</strong> (10–20 lei)', 'sage'), ['strong' => []]) !!}</span>
        </div>
        <div class="meta-row">
          <span class="k">{{ __('Rambursare', 'sage') }}</span>
          <span class="v">{!! wp_kses(__('Maxim <strong>14 zile</strong> de la primirea coletului', 'sage'), ['strong' => []]) !!}</span>
        </div>
        <p class="legal-ref">{{ __('Conform OUG 34/2014, art. 9 (dreptul de retragere).', 'sage') }}</p>
      </div>

      {{-- Defect sau expirat --}}
      <div class="case-card warn">
        <div class="stamp">
          <span class="ico"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg></span>
          {{ __('Da, condiții diferite', 'sage') }}
        </div>
        <h3>{!! wp_kses(__('Produs <em>defect</em> sau expirat.', 'sage'), ['em' => []]) !!}</h3>
        <ul>
          <li>{!! wp_kses(__('Primit <strong>deteriorat</strong>, cu sigiliul rupt, expirat sau cu lot defect', 'sage'), ['strong' => []]) !!}</li>
          <li>{!! wp_kses(__('Anunți în <strong>maximum 48h</strong> de la primire, cu poză', 'sage'), ['strong' => []]) !!}</li>
          <li>{{ __('Inclusiv pentru produse deschise dacă defectul e de fabricație', 'sage') }}</li>
        </ul>
        <div class="meta-row">
          <span class="k">{{ __('Cost transport retur', 'sage') }}</span>
          <span class="v">{!! wp_kses(__('<strong>Suportăm noi</strong> — primești AWB prepaid', 'sage'), ['strong' => []]) !!}</span>
        </div>
        <div class="meta-row">
          <span class="k">{{ __('Soluție', 'sage') }}</span>
          <span class="v">{!! wp_kses(__('<strong>Înlocuire imediată</strong> sau rambursare integrală, la alegerea ta', 'sage'), ['strong' => []]) !!}</span>
        </div>
        <p class="legal-ref">{{ __('Conform Legii 449/2003 (garanție 2 ani pentru defecte de conformitate).', 'sage') }}</p>
      </div>

      {{-- Desigilat --}}
      <div class="case-card no">
        <div class="stamp">
          <span class="ico"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M18 6 6 18M6 6l12 12"/></svg></span>
          {{ __('Nu se returnează', 'sage') }}
        </div>
        <h3>{!! wp_kses(__('Produs <em>desigilat</em> (deschis).', 'sage'), ['em' => []]) !!}</h3>
        <ul>
          <li>{!! wp_kses(__('Suplimentele alimentare desigilate <strong>nu fac obiectul retragerii</strong>', 'sage'), ['strong' => []]) !!}</li>
          <li>{{ __('Motiv: protecția sănătății și igiena consumatorului', 'sage') }}</li>
          <li>{{ __('Excepție: defect de fabricație → vezi cazul 2', 'sage') }}</li>
        </ul>
        <div class="meta-row" style="background:var(--color-gold-soft);border-left:3px solid var(--color-cta-primary)">
          <span class="k" style="color:#7A5530">{{ __('Dar dacă ai o problemă reală...', 'sage') }}</span>
          <span class="v" style="color:#5A3A1A;font-style:italic;font-family:var(--font-serif);font-weight:400">{!! wp_kses(__('Scrie-ne la <strong style="font-family:var(--font-sans);font-style:normal">office@mananaturii.ro</strong>. Pentru intoleranță, reacție sau gust pe care nu îl suporți, căutăm o soluție — credit produs sau schimb. <em>Nu garantăm, dar încercăm.</em>', 'sage'), ['strong' => ['style' => []], 'em' => []]) !!}</span>
        </div>
        <p class="legal-ref">{{ __('Conform OUG 34/2014, art. 16 lit. e (excepție de la dreptul de retragere).', 'sage') }}</p>
      </div>

    </div>
  </div>
</section>
