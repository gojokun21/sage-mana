{{-- Contact — 3 canale (WhatsApp / Email / Telefon). --}}
<section class="channels">
  <div class="channels-inner">
    <div class="channels-head">
      <span class="eyebrow">{{ __('Cel mai rapid', 'sage') }}</span>
    </div>
    <div class="channels-grid">

      <div class="ch-card featured">
        <span class="badge">{{ __('Recomandat', 'sage') }}</span>
        <div class="ico wa">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.5 14.4c-.3-.2-1.8-.9-2.1-1s-.5-.2-.7.2c-.2.3-.8 1-1 1.2-.2.2-.4.2-.7.1-.3-.2-1.3-.5-2.4-1.5-.9-.8-1.5-1.8-1.7-2.1-.2-.3 0-.5.1-.6.1-.1.3-.4.4-.5.1-.2.2-.3.3-.5.1-.2 0-.4 0-.5 0-.2-.7-1.7-1-2.3-.3-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.5s1.1 2.9 1.2 3.1c.2.2 2.1 3.2 5.1 4.5z"/></svg>
        </div>
        <h3>{{ __('WhatsApp.', 'sage') }}</h3>
        <span class="time">{!! wp_kses(__('Răspuns în <strong>maxim 2h</strong> · luni–vineri 9–17', 'sage'), ['strong' => []]) !!}</span>
        <div class="addr">+40 749 492 794</div>
        <a class="btn primary" href="https://wa.me/40749492794" target="_blank" rel="noopener">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.5 14.4c-.3-.2-1.8-.9-2.1-1s-.5-.2-.7.2c-.2.3-.8 1-1 1.2-.2.2-.4.2-.7.1-.3-.2-1.3-.5-2.4-1.5-.9-.8-1.5-1.8-1.7-2.1z"/></svg>
          {{ __('Deschide WhatsApp', 'sage') }}
        </a>
        <p class="desc">{{ __('Cel mai rapid — pentru întrebări urgente despre comenzi.', 'sage') }}</p>
      </div>

      <div class="ch-card">
        <div class="ico em">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="m22 6-10 7L2 6"/></svg>
        </div>
        <h3>{{ __('Email.', 'sage') }}</h3>
        <span class="time">{!! wp_kses(__('Răspuns în <strong>maxim 24h</strong> · luni–vineri', 'sage'), ['strong' => []]) !!}</span>
        <div class="addr">suport@mananaturii.ro</div>
        <a class="btn outline" href="mailto:suport@mananaturii.ro">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 2 11 13"/><path d="m22 2-7 20-4-9-9-4 20-7z"/></svg>
          {{ __('Trimite email', 'sage') }}
        </a>
        <p class="desc">{{ __('Pentru întrebări detaliate, retururi sau probleme complexe.', 'sage') }}</p>
      </div>

      <div class="ch-card">
        <div class="ico tel">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.37 1.9.72 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.35 1.85.59 2.81.72A2 2 0 0 1 22 16.92z"/></svg>
        </div>
        <h3>{{ __('Telefon.', 'sage') }}</h3>
        <span class="time">{!! wp_kses(__('Luni–vineri <strong>9:00–17:00</strong>', 'sage'), ['strong' => []]) !!}</span>
        <div class="addr">+40 749 492 794</div>
        <a class="btn outline" href="tel:+40749492794">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.37 1.9.72 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.35 1.85.59 2.81.72A2 2 0 0 1 22 16.92z"/></svg>
          {{ __('Sună', 'sage') }}
        </a>
        <p class="desc">{{ __('Pentru consultanță personalizată și discuții lungi.', 'sage') }}</p>
      </div>

    </div>
  </div>
</section>
