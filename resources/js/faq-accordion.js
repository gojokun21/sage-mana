/* FAQ accordion — smooth open/close animation for <details> elements.
 *
 * Native <details> toggles instantly. We intercept the summary click,
 * animate the answer's height + opacity via the Web Animations API, then
 * set the [open] attribute so the DOM state stays in sync with what's
 * visible. The native element keeps its accessibility semantics
 * (role=group / aria-expanded inferred from [open]).
 *
 * Behaviour:
 *  - Single-open accordion per .faq block (opening one collapses others).
 *  - Animation honors prefers-reduced-motion: users with that pref get the
 *    native instant toggle.
 *  - A `data-animating` flag prevents double-trigger on rapid clicks.
 */

const DURATION = 320;
const EASING = 'cubic-bezier(0.4, 0, 0.2, 1)';

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

function getAnswer(details) {
  return details.querySelector('.faq-a');
}

function animateOpen(details) {
  const a = getAnswer(details);
  if (!a || reduceMotion) {
    details.open = true;
    return;
  }
  if (details.dataset.animating) return;

  details.dataset.animating = 'open';
  details.open = true;

  const target = a.scrollHeight;
  const anim = a.animate(
    [
      { height: '0px', opacity: 0 },
      { height: target + 'px', opacity: 1 },
    ],
    { duration: DURATION, easing: EASING },
  );
  anim.onfinish = () => {
    delete details.dataset.animating;
  };
}

function animateClose(details) {
  const a = getAnswer(details);
  if (!a || reduceMotion) {
    details.open = false;
    return;
  }
  if (details.dataset.animating) return;

  details.dataset.animating = 'close';
  const current = a.getBoundingClientRect().height;
  const anim = a.animate(
    [
      { height: current + 'px', opacity: 1 },
      { height: '0px', opacity: 0 },
    ],
    { duration: DURATION, easing: EASING },
  );
  anim.onfinish = () => {
    details.open = false;
    delete details.dataset.animating;
  };
}

document.querySelectorAll('.faq').forEach((root) => {
  const items = Array.from(root.querySelectorAll('.faq-item'));

  items.forEach((details) => {
    const summary = details.querySelector('.faq-q');
    if (!summary) return;

    summary.addEventListener('click', (e) => {
      // preventDefault stops the native instant toggle so we can animate.
      e.preventDefault();
      const wasOpen = details.open;

      // Close any other open item in this block (single-open behaviour).
      items.forEach((other) => {
        if (other !== details && other.open) {
          animateClose(other);
        }
      });

      if (wasOpen) {
        animateClose(details);
      } else {
        animateOpen(details);
      }
    });
  });
});
