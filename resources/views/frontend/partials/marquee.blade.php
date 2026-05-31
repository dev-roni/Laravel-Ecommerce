{{-- ══════════════════════════════════════
     MARQUEE — @section('show_marquee') দিলে দেখাবে
══════════════════════════════════════ --}}
@hasSection('show_marquee')
  <div class="marquee-bar">
    <div class="marquee-track">
      @foreach(array_fill(0, 2, [
        ['icon' => 'fa-solid fa-truck',          'text' => 'Free Shipping Over ৳1,500'],
        ['icon' => 'fa-solid fa-rotate',         'text' => 'New Arrivals Every Week'],
        ['icon' => 'fa-solid fa-rotate-left',    'text' => '30-Day Easy Returns'],
        ['icon' => 'fa-solid fa-shield-halved',  'text' => 'Authentic Products Guaranteed'],
        ['icon' => 'fa-solid fa-tag',            'text' => 'Exclusive Members Deals'],
      ]) as $group)
        @foreach($group as $item)
          <div class="marquee-item">
            <i class="{{ $item['icon'] }}"></i> {{ $item['text'] }}
          </div>
        @endforeach
      @endforeach
    </div>
  </div>
@endif