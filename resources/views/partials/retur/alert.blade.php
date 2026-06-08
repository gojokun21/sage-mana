{{-- Retur — alertă „onestă": suplimentele desigilate nu se pot returna. --}}
<div class="honest-alert">
  <div class="honest-alert-inner">
    <h2>{!! wp_kses(__('Important: suplimentele <em>desigilate</em> nu se pot returna.', 'sage'), ['em' => []]) !!}</h2>
    <p>{!! wp_kses(__('Conform legii (<strong>OUG 34/2014, art. 16 lit. e</strong>), suplimentele alimentare desigilate nu se pot returna din motive de igienă și protecție a sănătății. Dacă ai deschis produsul, <strong>contactează-ne direct</strong> — căutăm o soluție caz cu caz.', 'sage'), ['strong' => []]) !!}</p>
    <a class="see-exc" href="#special-cases">{{ __('Vezi excepții și situații speciale', 'sage') }}
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
    </a>
  </div>
</div>
