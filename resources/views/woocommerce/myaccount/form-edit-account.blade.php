{{--
  My Account → Date personale (endpoint edit-account). Redesign vizual după mockup
  `preferinte/Cont - Date personale.html`, pe modelul NATIV WooCommerce. Un singur
  <form> WC: nume + email + parolă rămân native; telefon → billing_phone, data
  nașterii + gen → user meta (salvate în app/account-details.php prin hook-ul
  woocommerce_save_account_details). Editarea inline + modalul de parolă sunt
  vizuale (resources/js/account-details.js); salvarea e POST nativ WC. Scope `.dp-page`.
  Secțiunile fără backend din mockup (2FA, sesiuni, login social, GDPR/export/ștergere)
  sunt intenționat omise. $user vine de la WC.
  @see https://woocommerce.com/document/template-structure/
  @version 10.5.0
--}}
@php
  defined('ABSPATH') || exit;

  $user = $user ?? wp_get_current_user();
  $uid = $user->ID;

  $phone  = get_user_meta($uid, 'billing_phone', true);
  $bday   = get_user_meta($uid, 'mn_birthday', true);
  $gender = get_user_meta($uid, 'mn_gender', true);

  $gender_labels = [
    'feminin'      => __('Feminin', 'sage'),
    'masculin'     => __('Masculin', 'sage'),
    'nespecificat' => __('Prefer să nu spun', 'sage'),
  ];

  $full_name = trim($user->first_name . ' ' . $user->last_name);
  $bday_display = $bday ? wp_date('j F Y', strtotime($bday)) : '';

  do_action('woocommerce_before_edit_account_form');
@endphp

<div class="dp-page">

  <div class="page-head">
    <div class="eyebrow">{{ __('Cont · Date personale', 'sage') }}</div>
    <h1>{!! wp_kses_post(__('Datele tale, <em>protejate.</em>', 'sage')) !!}</h1>
    <p>{{ __('Modifică informațiile de contact sau schimbă parola. Datele se folosesc pentru livrare și comunicare — nu le partajăm cu terți.', 'sage') }}</p>
  </div>

  <form class="woocommerce-EditAccountForm edit-account dp-form" action="" method="post" @php do_action('woocommerce_edit_account_form_tag') @endphp>
    @php do_action('woocommerce_edit_account_form_start') @endphp

    {{-- CARD: Informații personale --}}
    <div class="data-card">
      <div class="card-head">
        <h2><span class="ico"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>{!! wp_kses_post(__('Informații <em>personale.</em>', 'sage')) !!}</h2>
        <p class="help">{{ __('Apasă „Modifică" pe orice câmp, schimbă valoarea și salvează.', 'sage') }}</p>
      </div>

      <div class="field-rows">

        {{-- Nume complet (first + last) --}}
        <div class="field-row" data-field="name">
          <span class="lbl">{{ __('Nume complet', 'sage') }}</span>
          <div class="val">
            <div class="val-display">{{ $full_name !== '' ? $full_name : $user->display_name }}</div>
            <div class="val-edit">
              <div class="name-inputs">
                <input type="text" name="account_first_name" value="{{ esc_attr($user->first_name) }}" data-original="{{ esc_attr($user->first_name) }}" placeholder="{{ esc_attr__('Prenume', 'sage') }}" autocomplete="given-name" aria-required="true" />
                <input type="text" name="account_last_name" value="{{ esc_attr($user->last_name) }}" data-original="{{ esc_attr($user->last_name) }}" placeholder="{{ esc_attr__('Nume', 'sage') }}" autocomplete="family-name" aria-required="true" />
              </div>
              <div class="row-actions">
                <button type="submit" class="save-btn"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>{{ __('Salvează', 'sage') }}</button>
                <button type="button" class="cancel-btn" data-cancel>{{ __('Anulează', 'sage') }}</button>
              </div>
            </div>
          </div>
          <div class="actions-cell"><button type="button" class="edit-btn" data-edit><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="m18.5 2.5 3 3L12 15l-4 1 1-4z"/></svg>{{ __('Modifică', 'sage') }}</button></div>
        </div>

        {{-- Email --}}
        <div class="field-row" data-field="email">
          <span class="lbl">{{ __('Email', 'sage') }}</span>
          <div class="val">
            <div class="val-display">{{ $user->user_email }}</div>
            <div class="val-edit">
              <input type="email" name="account_email" value="{{ esc_attr($user->user_email) }}" data-original="{{ esc_attr($user->user_email) }}" autocomplete="email" aria-required="true" />
              <div class="row-actions">
                <button type="submit" class="save-btn"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>{{ __('Salvează', 'sage') }}</button>
                <button type="button" class="cancel-btn" data-cancel>{{ __('Anulează', 'sage') }}</button>
              </div>
            </div>
          </div>
          <div class="actions-cell"><button type="button" class="edit-btn" data-edit><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="m18.5 2.5 3 3L12 15l-4 1 1-4z"/></svg>{{ __('Modifică', 'sage') }}</button></div>
        </div>

        {{-- Telefon --}}
        <div class="field-row" data-field="phone">
          <span class="lbl">{{ __('Telefon', 'sage') }}</span>
          <div class="val">
            <div class="val-display">@if ($phone){{ $phone }}@else<span class="empty">{{ __('Nu e setat', 'sage') }}</span><span class="helper">{{ __('Îl folosim ca să te contacteze curierul.', 'sage') }}</span>@endif</div>
            <div class="val-edit">
              <input type="tel" name="account_phone" value="{{ esc_attr($phone) }}" data-original="{{ esc_attr($phone) }}" placeholder="07xx xxx xxx" autocomplete="tel" />
              <div class="row-actions">
                <button type="submit" class="save-btn"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>{{ __('Salvează', 'sage') }}</button>
                <button type="button" class="cancel-btn" data-cancel>{{ __('Anulează', 'sage') }}</button>
              </div>
            </div>
          </div>
          <div class="actions-cell"><button type="button" class="edit-btn" data-edit>@if ($phone)<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="m18.5 2.5 3 3L12 15l-4 1 1-4z"/></svg>{{ __('Modifică', 'sage') }}@else<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>{{ __('Adaugă', 'sage') }}@endif</button></div>
        </div>

        {{-- Data nașterii --}}
        <div class="field-row" data-field="bday">
          <span class="lbl">{{ __('Data nașterii', 'sage') }}</span>
          <div class="val">
            <div class="val-display">@if ($bday){{ $bday_display }}@else<span class="empty">{{ __('Nu e setată', 'sage') }}</span><span class="helper">{{ __('Pentru un mesaj la zi de naștere. Nu e obligatoriu.', 'sage') }}</span>@endif</div>
            <div class="val-edit">
              <input type="date" name="account_bday" value="{{ esc_attr($bday) }}" data-original="{{ esc_attr($bday) }}" />
              <div class="row-actions">
                <button type="submit" class="save-btn"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>{{ __('Salvează', 'sage') }}</button>
                <button type="button" class="cancel-btn" data-cancel>{{ __('Anulează', 'sage') }}</button>
              </div>
            </div>
          </div>
          <div class="actions-cell"><button type="button" class="edit-btn" data-edit>@if ($bday)<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="m18.5 2.5 3 3L12 15l-4 1 1-4z"/></svg>{{ __('Modifică', 'sage') }}@else<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>{{ __('Adaugă', 'sage') }}@endif</button></div>
        </div>

        {{-- Gen --}}
        <div class="field-row" data-field="gender">
          <span class="lbl">{{ __('Gen', 'sage') }}</span>
          <div class="val">
            <div class="val-display">@if ($gender && isset($gender_labels[$gender])){{ $gender_labels[$gender] }}@else<span class="empty">{{ __('Nu e setat', 'sage') }}</span><span class="helper">{{ __('Doar pentru sugestii relevante.', 'sage') }}</span>@endif</div>
            <div class="val-edit">
              <select name="account_gender" data-original="{{ esc_attr($gender) }}">
                <option value="">{{ __('Alege...', 'sage') }}</option>
                @foreach ($gender_labels as $slug => $label)
                  <option value="{{ $slug }}" @selected($gender === $slug)>{{ $label }}</option>
                @endforeach
              </select>
              <div class="row-actions">
                <button type="submit" class="save-btn"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>{{ __('Salvează', 'sage') }}</button>
                <button type="button" class="cancel-btn" data-cancel>{{ __('Anulează', 'sage') }}</button>
              </div>
            </div>
          </div>
          <div class="actions-cell"><button type="button" class="edit-btn" data-edit>@if ($gender)<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="m18.5 2.5 3 3L12 15l-4 1 1-4z"/></svg>{{ __('Modifică', 'sage') }}@else<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>{{ __('Adaugă', 'sage') }}@endif</button></div>
        </div>

      </div>
    </div>

    {{-- CARD: Securitate (doar parola — restul mockup-ului nu are backend) --}}
    <div class="data-card">
      <div class="card-head">
        <h2><span class="ico"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></span>{!! wp_kses_post(__('Securitate <em>cont.</em>', 'sage')) !!}</h2>
        <p class="help">{{ __('Schimbă parola cu care te autentifici.', 'sage') }}</p>
      </div>
      <div>
        <div class="sec-row">
          <div class="ico"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></div>
          <div class="info">
            <span class="ttl">{{ __('Parolă', 'sage') }}</span>
            <span class="desc"><span class="dots">••••••••</span> · {{ __('schimb-o periodic pentru siguranță', 'sage') }}</span>
          </div>
          <div class="row-cta"><button type="button" class="edit-btn" data-modal-open><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="m18.5 2.5 3 3L12 15l-4 1 1-4z"/></svg>{{ __('Schimbă parola', 'sage') }}</button></div>
        </div>
      </div>
    </div>

    {{-- Câmpuri ascunse cerute de WC + nonce + acțiune --}}
    <input type="hidden" name="account_display_name" value="{{ esc_attr($user->display_name) }}" />
    @php do_action('woocommerce_edit_account_form_fields') @endphp
    @php do_action('woocommerce_edit_account_form') @endphp
    @php wp_nonce_field('save_account_details', 'save-account-details-nonce') @endphp
    <input type="hidden" name="action" value="save_account_details" />

    {{-- MODAL: schimbă parola (în interiorul formularului → POST nativ WC) --}}
    <div class="modal-backdrop" id="dpModalPw">
      <div class="modal">
        <div class="modal-head">
          <div class="ico"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></div>
          <div class="copy">
            <h3>{!! wp_kses_post(__('Schimbă <em>parola.</em>', 'sage')) !!}</h3>
            <p>{{ __('Folosește minim 8 caractere, cu litere și cifre.', 'sage') }}</p>
          </div>
          <button type="button" class="close" data-modal-close><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
        </div>
        <div class="modal-body">
          <div class="field">
            <label for="password_current">{{ __('Parolă actuală', 'sage') }}</label>
            <input type="password" name="password_current" id="password_current" autocomplete="current-password" placeholder="••••••••" />
          </div>
          <div class="field">
            <label for="password_1">{{ __('Parolă nouă', 'sage') }}</label>
            <input type="password" name="password_1" id="password_1" autocomplete="new-password" placeholder="{{ esc_attr__('Cel puțin 8 caractere', 'sage') }}" />
            <div class="pw-strength" id="dpPwMeter"><span class="bar"></span><span class="bar"></span><span class="bar"></span></div>
            <span class="pw-helper">{{ __('Minim 8 caractere, cu litere și cifre.', 'sage') }}</span>
          </div>
          <div class="field">
            <label for="password_2">{{ __('Confirmă parola nouă', 'sage') }}</label>
            <input type="password" name="password_2" id="password_2" autocomplete="new-password" placeholder="••••••••" />
          </div>
        </div>
        <div class="modal-actions">
          <button type="button" class="cancel" data-modal-close>{{ __('Anulează', 'sage') }}</button>
          <button type="submit" class="confirm"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>{{ __('Salvează parola', 'sage') }}</button>
        </div>
      </div>
    </div>

    @php do_action('woocommerce_edit_account_form_end') @endphp
  </form>

</div>

@php do_action('woocommerce_after_edit_account_form') @endphp
