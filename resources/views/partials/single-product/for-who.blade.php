{{-- PDP — pentru cine merge / nu merge (static). --}}
@php
  $yes = [
    __('Persoane care vor susținere imunitară zilnică', 'sage'),
    __('Cei cu sensibilitate cardiovasculară (familie cu istoric)', 'sage'),
    __('Perioade de stres sau oboseală cronică', 'sage'),
    __('Oricine vrea o sursă naturală de antioxidanți', 'sage'),
  ];
  $no = [
    __('Minori sub 12 ani', 'sage'),
    __('Alergie la Nigella sativa sau familia Ranunculaceae', 'sage'),
    __('Femei însărcinate sau care alăptează — consultă medicul', 'sage'),
    __('Persoane sub tratament anticoagulant — consultă medicul', 'sage'),
  ];
@endphp
<section class="pcine">
  <div class="pcine-inner">
    <div class="pcine-head">
      <span class="eyebrow">{{ __('Onestitate produs', 'sage') }}</span>
      <h2>{{ __('Pentru cine', 'sage') }} <em>{{ __('merge bine', 'sage') }}</em>. {{ __('Și pentru cine', 'sage') }} <em>{{ __('nu merge (încă)', 'sage') }}</em>.</h2>
    </div>
    <div class="pcine-grid">
      <div class="pcine-col yes">
        <h3>{{ __('Merge bine pentru...', 'sage') }}</h3>
        <ul>
          @foreach ($yes as $item)
            <li>{{ $item }}</li>
          @endforeach
        </ul>
      </div>
      <div class="pcine-col no">
        <h3>{{ __('Nu merge (încă) pentru...', 'sage') }}</h3>
        <ul>
          @foreach ($no as $item)
            <li>{{ $item }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</section>
