{{-- Contact — program suport pe canale. --}}
<section class="program">
  <div class="program-inner">
    <div class="program-head">
      <div class="eyebrow">{{ __('Program suport', 'sage') }}</div>
      <h2>{{ __('Când suntem', 'sage') }} <em>{{ __('disponibili.', 'sage') }}</em></h2>
    </div>
    <div class="program-table">
      <table>
        <thead>
          <tr>
            <th>{{ __('Canal', 'sage') }}</th>
            <th>{{ __('Luni–Vineri', 'sage') }}</th>
            <th>{{ __('Sâmbătă', 'sage') }}</th>
            <th>{{ __('Duminică', 'sage') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="ch"><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.5 14.4c-.3-.2-1.8-.9-2.1-1s-.5-.2-.7.2c-.2.3-.8 1-1 1.2-.2.2-.4.2-.7.1-.3-.2-1.3-.5-2.4-1.5-.9-.8-1.5-1.8-1.7-2.1z"/></svg>WhatsApp</td>
            <td class="t-on">9:00–17:00</td>
            <td class="t-on">10:00–14:00</td>
            <td class="t-off">—</td>
          </tr>
          <tr>
            <td class="ch"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.37 1.9.72 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.35 1.85.59 2.81.72A2 2 0 0 1 22 16.92z"/></svg>{{ __('Telefon', 'sage') }}</td>
            <td class="t-on">9:00–17:00</td>
            <td class="t-off">—</td>
            <td class="t-off">—</td>
          </tr>
          <tr>
            <td class="ch"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="m22 6-10 7L2 6"/></svg>Email</td>
            <td class="t-24" colspan="3">{{ __('Trimite oricând · răspuns în 24h L–V', 'sage') }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <p class="program-foot">{!! wp_kses(__('În afara programului răspundem la <strong>primul ceas L–V</strong>. Comenzile online se procesează zilnic, inclusiv weekend.', 'sage'), ['strong' => []]) !!}</p>
  </div>
</section>
