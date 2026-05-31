{{--
  partials/_cat_item.blade.php
  Variables:
    $category  — Category model instance
    $depth     — current nesting depth (0 = top-level inside .cat-dropdown)

  Usage (root call):
    @include('partials._cat_item', ['category' => $cat, 'depth' => 0])
--}}

@php
  $children = $category->activeChildren()  
             ?? $category->children()
                          ->where('is_active', true)
                          ->orderBy('order')
                          ->get();
  $hasKids  = $children->count() > 0;
@endphp

<div class="cat-dropdown-item {{ $hasKids ? 'cat-has-children' : '' }}">

  {{-- Item link --}}
  <a href="{{ route('shop.category', $category->slug) }}">
    <span class="d-flex align-items-center gap-2">
      @if($category->image)
        <img src="{{ Storage::url($category->image) }}"
             style="width:18px;height:18px;object-fit:cover;border-radius:3px;flex-shrink:0">
      @else
        <i class="fa-solid {{ $hasKids ? 'fa-folder' : 'fa-folder-open' }}"
           style="color:var(--secondary);font-size:.78rem;width:16px;text-align:center;flex-shrink:0"></i>
      @endif
      {{ $category->name }}
    </span>
    @if($hasKids)
      <i class="fa-solid fa-chevron-left cat-arrow"></i>
    @else
      <span class="cat-sub-dot ms-auto"></span>
    @endif
  </a>

  {{-- Flyout panel for children --}}
  @if($hasKids)
    <div class="cat-sub-panel">

      {{-- "View all" header --}}
      <a href="{{ route('shop.category', $category->slug) }}" class="cat-view-all">
        <i class="fa-solid fa-layer-group"
           style="color:var(--secondary);font-size:.72rem;width:14px;text-align:center;"></i>
        সব {{ $category->name }}
      </a>

      {{-- Recurse into each child --}}
      @foreach($children as $child)
        @include('frontend.partials._cat_item', ['category' => $child, 'depth' => $depth + 1])
      @endforeach

    </div>
  @endif

</div>
