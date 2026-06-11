/* Cont · Abonamente (.mn-subs-account) — flatpickr pe câmpul „Data nouă".
 *
 * Calendar consistent cross-browser, în română. Valoarea trimisă rămâne `Y-m-d`
 * (exact ca input-ul nativ type=date), ca handler-ul AJAX din plugin
 * (mn-subscriptions / subscriptions-account.js → collectFields citește
 * `[data-field="date"].value`) s-o primească neschimbat. `altInput` arată o dată
 * lizibilă, dar input-ul original (ascuns) păstrează `data-field` + valoarea Y-m-d.
 */

import flatpickr from 'flatpickr';
import { Romanian } from 'flatpickr/dist/l10n/ro.js';
import 'flatpickr/dist/flatpickr.min.css';

document.querySelectorAll('.mn-subs-account [data-field="date"]').forEach(function (input) {
  flatpickr(input, {
    locale: Romanian,
    dateFormat: 'Y-m-d',
    altInput: true,
    altFormat: 'j F Y',
    minDate: 'today',
    disableMobile: true,
  });
});
