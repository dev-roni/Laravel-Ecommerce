
<div class="prod-card h-100">

  {{-- Image Zone --}}
  <a href="{{ route('shop.product', $product->slug) }}">
    <div class="prod-img-zone">

      @if($product->primaryImage)
        <img src="{{ Storage::url($product->primaryImage?->image ?? $product->images->first()->image) }}"
            alt="{{ $product->name }}"
            loading="lazy">
      @else
        <span>📦</span>
      @endif

      {{-- Badge --}}
      @if($product->total_stock <= 0)
        <span class="prod-badge badge" style="background:var(--error)">Stock নেই</span>
      @elseif($product->discount_percent)
        <span class="prod-badge badge" style="background:var(--accent)">
          −{{ $product->discount_percent }}%
        </span>
      @elseif($product->created_at->isAfter(now()->subDays(7)))
        <span class="prod-badge badge" style="background:var(--success)">New</span>
      @elseif($product->is_featured)
        <span class="prod-badge badge" style="background:var(--secondary)">Best</span>
      @endif

    </div>
  </a>

  {{-- Card Body --}}
  <div class="p-3">

    <div class="prod-cat mb-1">
      {{ $product->category->name ?? '' }}
    </div>

    <a href="{{ route('shop.product', $product->slug) }}"
       class="prod-name mb-2">
      {{ Str::limit($product->name, 45) }}
    </a>

    {{-- Low stock warning --}}
    @if($product->total_stock > 0 && $product->total_stock <= 5)
      <div style="font-size:.65rem;color:var(--accent);font-weight:600;margin-bottom:.35rem">
        <i class="fa-solid fa-triangle-exclamation me-1"></i>
        মাত্র {{ $product->total_stock }}টি বাকি
      </div>
    @endif

    {{-- Price + Cart --}}
    <div class="d-flex align-items-center justify-content-between mt-auto">
      <div>
        <span class="prod-price">
          ৳{{ number_format($product->current_price) }}
        </span>
        @if($product->sale_price)
          <span class="prod-price-old ms-1">
            ৳{{ number_format($product->base_price) }}
          </span>
        @endif
      </div>

      @if($product->has_variants)
        <a href="{{ route('shop.product', $product->slug) }}"
           class="btn-add-cart"
           title="Choose options"
           style="text-decoration:none">
          <i class="fa-solid fa-arrow-right"></i>
        </a>
      @else
        <button class="btn-add-cart"
                onclick="addToCart({{ $product->id }})"
                title="Add to Cart"
                {{ $product->total_stock <= 0 ? 'disabled style=opacity:.4' : '' }}>
          <i class="fa-solid fa-plus fa-lg"></i>
        </button>
      @endif
    </div>

  </div>
</div>
