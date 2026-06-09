{{-- 4-step cancel off-ramp modal. Driven by subscriptions-account.js (CancelFlow). --}}
<div class="mn-subs-modal" data-cancel-modal aria-modal="true" role="dialog">
  <div class="mn-subs-modal__box">

    {{-- Step 1 — reason --}}
    <div data-step="1">
      <h4>{!! wp_kses_post(__('Spune-ne <em>de ce.</em>', 'sage')) !!}</h4>
      <ul class="mn-subs-reasons">
        <li><label><input type="radio" name="cancel_reason" value="nu_mi_a_placut" /> {{ __('Nu mi-a plăcut produsul', 'sage') }}</label></li>
        <li><label><input type="radio" name="cancel_reason" value="prea_scump" checked /> {{ __('Prea scump', 'sage') }}</label></li>
        <li><label><input type="radio" name="cancel_reason" value="nu_imi_mai_foloseste" /> {{ __('Nu îmi mai folosește', 'sage') }}</label></li>
        <li><label><input type="radio" name="cancel_reason" value="schimb_cu_altceva" /> {{ __('Schimb cu altceva', 'sage') }}</label></li>
        <li><label><input type="radio" name="cancel_reason" value="alt_motiv" /> {{ __('Alt motiv', 'sage') }}</label></li>
      </ul>
      <div class="mn-subs-modal__actions">
        <button class="mn-subs-btn mn-subs-btn--primary" data-cancel-reason-submit>{{ __('Continuă', 'sage') }}</button>
        <button class="mn-subs-link-btn" data-cancel-close>{{ __('Renunț, păstrez abonamentul', 'sage') }}</button>
      </div>
    </div>

    {{-- Step 2 — retention offer --}}
    <div data-step="2" hidden>
      <h4>{!! wp_kses_post(__('Stai puțin — <em>te putem ajuta?</em>', 'sage')) !!}</h4>
      <div class="mn-subs-modal__recap">
        {{ __('Îți putem oferi o reducere suplimentară pe următoarele livrări sau o pauză, în loc să oprești de tot.', 'sage') }}
      </div>
      <div class="mn-subs-modal__actions">
        <button class="mn-subs-btn mn-subs-btn--primary" data-cancel-offer="discount">{{ __('Vreau reducerea', 'sage') }}</button>
        <button class="mn-subs-btn" data-cancel-offer="pause" data-months="1">{{ __('Pauză 1 lună', 'sage') }}</button>
        <button class="mn-subs-link-btn" data-cancel-decline>{{ __('Nu, continuă oprirea →', 'sage') }}</button>
      </div>
    </div>

    {{-- Step 3 — confirm --}}
    <div data-step="3" hidden>
      <h4>{!! wp_kses_post(__('Confirmare <em>finală.</em>', 'sage')) !!}</h4>
      <div class="mn-subs-modal__recap">
        {{ __('Vei opri acest abonament. Nu se va mai face nicio livrare după ultima confirmată.', 'sage') }}
      </div>
      <div class="mn-subs-modal__actions">
        <button class="mn-subs-btn mn-subs-btn--terra" data-cancel-confirm>{{ __('Confirmă oprirea', 'sage') }}</button>
        <button class="mn-subs-link-btn" data-cancel-close>{{ __('← Înapoi', 'sage') }}</button>
      </div>
    </div>

    {{-- Step 4 — thanks --}}
    <div data-step="4" hidden>
      <h4>{!! wp_kses_post(__('Mulțumim pentru <em>încredere.</em>', 'sage')) !!}</h4>
      <p>{{ __('Abonamentul tău este oprit. Vei primi un email de confirmare.', 'sage') }}</p>
      <div class="mn-subs-modal__actions">
        <button class="mn-subs-btn mn-subs-btn--primary" data-cancel-finish>{{ __('Închide', 'sage') }}</button>
      </div>
    </div>

  </div>
</div>
