{{--
  partials/_mob_cat_item.blade.php
  Recursive mobile offcanvas category tree.
  Variables: $category, $depth (int, starts at 0)
--}}

@php
  $children    = $category->children()->where('is_active', true)->orderBy('order')->get();
  $hasKids     = $children->count() > 0;
  $pl          = 1.25 + ($depth * 1.1);   // indent per level (rem)
  $iconSize    = $depth === 0 ? '.9rem' : '.75rem';
  $iconOpacity = $depth === 0 ? '1' : '.55';
  $iconClass   = $depth === 0 ? 'fa-folder' : ($hasKids ? 'fa-folder-open' : 'fa-minus');
@endphp

@if($hasKids)

  {{-- ── Toggle button ── --}}
  <div class="mob-parent-row"
     style="padding-left:{{ $pl }}rem">

    <div class="mob-parent-toggle"
         onclick="toggleMobSub(this, 'mob-sub-{{ $category->id }}')">

        <i class="fa-solid {{ $iconClass }}"
           style="width:18px;text-align:center;font-size:{{ $iconSize }};opacity:{{ $iconOpacity }};flex-shrink:0"></i>

        <a href="{{ route('shop.category', $category->slug) }}"
           class="mob-parent-link"
           onclick="event.stopPropagation()">

            {{ $category->name }}
        </a>

        <i class="fa-solid fa-chevron-right mob-toggle-icon"></i>

    </div>

</div>

  {{-- ── Collapsible sub-panel (children render INSIDE this div) ── --}}
  <div class="mob-sub-links" id="mob-sub-{{ $category->id }}">

    {{-- Recurse into each child — they live INSIDE this .mob-sub-links --}}
    @foreach($children as $child)
      @include('frontend.partials._mob_cat_item', ['category' => $child, 'depth' => $depth + 1])
    @endforeach

  </div>

@else

  {{-- ── Leaf link ── --}}
  <a href="{{ route('shop.category', $category->slug) }}"
     class="mob-sub-link"
     style="padding-left:{{ $pl }}rem"
     >
    <i class="fa-solid {{ $iconClass }}"
       style="width:18px;text-align:center;font-size:{{ $iconSize }};opacity:{{ $iconOpacity }};flex-shrink:0"></i>
    {{ $category->name }}
  </a>

@endif
