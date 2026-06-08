{{-- PDP — recenzii reale WooCommerce (comentarii de tip review pe produs). --}}
@php
  global $product;
  if (! $product instanceof \WC_Product) {
      $product = wc_get_product(get_the_ID());
  }

  if (! $product) {
      return;
  }

  $product_id = $product->get_id();
  $avg = round((float) $product->get_average_rating(), 1);
  $count = (int) $product->get_review_count();

  // Fără recenzii → ascundem complet secțiunea.
  if ($count < 1) {
      return;
  }

  // Aducem cele mai recente comentarii aprobate de pe produs, păstrăm doar pe
  // cele cu rating + text (recenzii reale, nu răspunsuri ale magazinului).
  $raw = get_comments([
      'post_id' => $product_id,
      'status'  => 'approve',
      'parent'  => 0,
      'number'  => 12,
      'orderby' => 'comment_date_gmt',
      'order'   => 'DESC',
  ]);

  $reviews = [];
  foreach ($raw as $c) {
      $rating = (int) get_comment_meta($c->comment_ID, 'rating', true);
      $text = trim($c->comment_content);
      if ($rating < 1 || $text === '') {
          continue;
      }

      $name = $c->comment_author ?: __('Client', 'sage');
      $reviews[] = [
          'initial'  => function_exists('mb_substr') ? mb_strtoupper(mb_substr($name, 0, 1)) : strtoupper(substr($name, 0, 1)),
          'name'     => $name,
          'date'     => date_i18n('j F Y', strtotime($c->comment_date)),
          'verified' => get_comment_meta($c->comment_ID, 'verified', true) === '1',
          'rating'   => max(1, min(5, $rating)),
          'text'     => $text,
      ];

      if (count($reviews) >= 3) {
          break;
      }
  }

  if (empty($reviews)) {
      return;
  }

  // Helper stele: pline + goale până la 5.
  $stars = static fn (int $n): string => str_repeat('★', $n) . str_repeat('☆', 5 - $n);

  $avg_display = number_format($avg, 1, ',', '.');
  $count_label = $count === 1
      ? __('dintr-o recenzie', 'sage')
      : sprintf(__('din %d recenzii', 'sage'), $count);
@endphp
<section class="rev">
  <div class="rev-inner">
    <div class="rev-head">
      <div>
        <span class="eyebrow">{{ __('Recenzii verificate', 'sage') }}</span>
        <h2>{{ __('Ce spun cei care îl iau de', 'sage') }} <em>{{ __('cel puțin 3 luni', 'sage') }}</em>.</h2>
      </div>
      <div class="score-box">
        <div class="num">{{ $avg_display }}</div>
        <div class="stars" aria-hidden="true">{{ $stars((int) round($avg)) }}</div>
        <div class="cnt">{{ $count_label }}</div>
      </div>
    </div>
    <div class="rev-grid">
      @foreach ($reviews as $r)
        <div class="rev-card">
          <div class="who">
            <div class="avatar" aria-hidden="true">{{ $r['initial'] }}</div>
            <div class="meta">
              <div class="name">{{ $r['name'] }}</div>
              <div class="info">{{ $r['date'] }}</div>
            </div>
          </div>
          <div class="rating-row">
            <span class="stars" aria-label="{{ sprintf(__('%d din 5 stele', 'sage'), $r['rating']) }}">{{ $stars($r['rating']) }}</span>
            @if ($r['verified'])
              <span class="verified">{{ __('Cumpărător verificat', 'sage') }}</span>
            @endif
          </div>
          <blockquote>{{ $r['text'] }}</blockquote>
        </div>
      @endforeach
    </div>
  </div>
</section>
