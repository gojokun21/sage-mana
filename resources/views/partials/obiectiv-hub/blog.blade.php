{{--
  Secțiune blog „Din jurnalul nostru · obiective". Carduri statice cu href „#"
  placeholder (decizie agreată — articolele se leagă ulterior). Structură identică
  cu partials/symptom/blog.
--}}
<section class="blog">
  <div class="blog-inner">
  <div class="blog-head">
    <div>
      <div class="eyebrow" style="margin-bottom:14px">{{ __('Din jurnalul nostru · obiective', 'sage') }}</div>
      <h2>{{ __('Dacă vrei să înțelegi mai întâi,', 'sage') }} <em>{{ __('apoi să acționezi.', 'sage') }}</em></h2>
    </div>
  </div>
  <div class="blog-grid">
    <a class="blog-card" href="#">
      <div class="blog-thumb t1">
        <span class="cat">{{ __('Ghid', 'sage') }}</span>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
      </div>
      <div class="meta">{{ __('Ghid · 8 min citire', 'sage') }}</div>
      <h3>{{ __('Cum alegi un singur obiectiv și nu te împrăștii pe cinci.', 'sage') }}</h3>
    </a>
    <a class="blog-card" href="#">
      <div class="blog-thumb t2">
        <span class="cat">{{ __('Cercetare', 'sage') }}</span>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="9"/><circle cx="9" cy="10" r="1.5" fill="currentColor"/><circle cx="14" cy="9" r="1.2" fill="currentColor"/></svg>
      </div>
      <div class="meta">{{ __('Cercetare · 11 min citire', 'sage') }}</div>
      <h3>{{ __('Energie, imunitate, focus: ce ingrediente au dovezi reale.', 'sage') }}</h3>
    </a>
    <a class="blog-card" href="#">
      <div class="blog-thumb t3">
        <span class="cat">{{ __('Practic', 'sage') }}</span>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M9 9h6M9 13h6M9 17h4"/></svg>
      </div>
      <div class="meta">{{ __('Practic · 6 min citire', 'sage') }}</div>
      <h3>{{ __('Cum măsori progresul spre un obiectiv, fără să te obsedezi.', 'sage') }}</h3>
    </a>
  </div>
  <div class="blog-foot"><a href="#">{{ __('Toate articolele despre obiective →', 'sage') }}</a></div>
  </div>
</section>
