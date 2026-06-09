{{-- resources/views/shop/product.blade.php --}}
@extends('frontend.layouts.masterLayout')
@section('title', $product->name)

@push('styles')
<style>


  .gallery-wrap { 
        position: sticky; top: 90px; 
    }

.gallery-wrap img{
    transition: transform .5s ease;
    display: block;
}
.gallery-wrap:hover img { transform: scale(1.04); }

  .variant-info{
  background: var(--background);
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: .85rem 1.1rem;
  margin-bottom: 1.25rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  flex-wrap: wrap;
  font-size: .82rem;
}
.v-info-item { display: flex; flex-direction: column; gap: .15rem; }
.v-info-label { font-size: .65rem; letter-spacing: .1em; text-transform: uppercase; color: var(--text-secondary); }
.v-info-value { font-weight: 600; color: var(--primary); }

  /* Meta accordion */
.meta-accordion { margin-top: 1.5rem; }
.meta-item {
  border-top: 1px solid var(--border);
  overflow: hidden;
}
.meta-item:last-child { border-bottom: 1px solid var(--border); }
.meta-trigger {
  width: 100%;
  background: transparent;
  border: none;
  padding: 1rem 0;
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-family: 'DM Sans', sans-serif;
  font-size: .82rem;
  font-weight: 600;
  letter-spacing: .06em;
  text-transform: uppercase;
  color: var(--text-primary);
  cursor: pointer;
  transition: color .2s;
}
.meta-trigger:hover { color: var(--secondary); }
.meta-trigger .meta-icon {
  font-size: .75rem;
  color: var(--text-secondary);
  transition: transform .25s;
}
.meta-trigger.open .meta-icon { transform: rotate(45deg); }
.meta-body {
  max-height: 0;
  overflow: hidden;
  transition: max-height .35s cubic-bezier(0,.9,.57,1);
}
.meta-body.open { max-height: 400px; }
.meta-content {
  padding: 0 0 1.1rem;
  font-size: .85rem;
  color: var(--text-secondary);
  line-height: 1.75;
}

.discount-chip {
  display:inline-block;
  background:rgba(239,68,68,.09); color:var(--error);
  border:1px solid rgba(239,68,68,.2);
  border-radius:4px; font-size:.66rem; font-weight:700;
  letter-spacing:.06em; text-transform:uppercase;
  padding:.22rem .6rem; margin-left:.45rem;
}

.cat-pill {
  display:inline-flex; align-items:center; gap:.4rem;
  font-size:.65rem; letter-spacing:.12em; text-transform:uppercase;
  font-weight:600; color:var(--secondary);
  background:rgba(29,161,168,.09);
  border:1px solid rgba(29,161,168,.22);
  border-radius:50px; padding:.28rem .85rem;
  margin-bottom:.85rem; text-decoration:none;
}
.cat-pill:hover { color:var(--secondary); background:rgba(29,161,168,.15); }
  </style>
  @endpush
@section('content')
<div class="container py-4">

    {{-- Breadcrumb --}}
    <nav class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item text-primary">
                <a href="{{ route('shop.index') }}">Home</a>
            </li>
            <li class="breadcrumb-item text-primary">
                <a href="{{ route('shop.category', $product->category->slug) }}">
                    {{ $product->category->name }}
                </a>
            </li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-5 anim-up">

        {{-- ছবি Gallery --}}
        <div class="col-md-5 d1">
          <div class="gallery-wrap">
            {{-- Main Image --}}
            <div class="mb-3">
                @if($product->images->count())
                    <img id="main-image"
                         src="{{ Storage::url($product->primaryImage?->image ?? $product->images->first()->image) }}"
                         alt="{{ $product->name }}"
                         class="img-fluid rounded-3 w-100"
                         style="height:380px;object-fit:cover">
                @else
                    <div class="bg-light rounded-3 d-flex align-items-center
                                justify-content-center"
                         style="height:380px;font-size:80px">
                        📦
                    </div>
                @endif
            </div>

            {{-- Thumbnails --}}
            @if($product->images->count() > 1)
                <div class="d-flex gap-2 flex-wrap">
                    @foreach($product->images as $image)
                        <img src="{{ Storage::url($image->image) }}"
                             alt=""
                             style="width:70px;height:70px;object-fit:cover;
                                    border-radius:8px;cursor:pointer;
                                    border:2px solid {{ $image->is_primary ? '#0d6efd' : '#dee2e6' }}"
                             onclick="document.getElementById('main-image').src = this.src;
                                      document.querySelectorAll('.thumb').forEach(t => t.style.borderColor='#dee2e6');
                                      this.style.borderColor='#0d6efd'">
                    @endforeach
                </div>
            @endif
          </div>
        </div>

        {{-- Product Info --}}
        <div class="col-md-7">

            <span class="cat-pill d1">
                <i class="fa-solid fa-folder" style="font-size:.58rem"></i>
                {{ $product->category->name }}
            </span>

            <h2 class="text-primary fw-bold mb-2 d1">{{ $product->name }}</h2>

            @if($product->brand)
                <p class="text-muted small mb-3 d1">Brand: {{ $product->brand }}</p>
            @endif

            {{-- দাম --}}
            <div class="mb-4 d2">
                <span class="fs-2 fw-bold text-primary" id="present-price">
                    ৳{{ number_format($product->current_price) }}
                </span>
                @if($product->sale_price)
                    <span id="original-price" class="text-muted text-decoration-line-through ms-2">
                        ৳{{ number_format($product->base_price) }}
                    </span>
                    <span id="commission" class="discount-chip ms-2">
                        {{ $product->discount_percent }}% ছাড়
                    </span>
                @endif
            </div>

            {{-- Short Description --}}
            @if($product->short_description)
                <p class="text-muted mb-4 d3">{{ $product->short_description }}</p>
            @endif

            {{-- Variant Selector --}}

            @if($product->has_variants && $product->activeVariants->count())
                <div id="variant-selector" class="mb-4 d4">

                    @php
                        $attributeGroups = [];
                        foreach ($product->activeVariants as $variant) {
                            foreach ($variant->attributeValues as $value) {
                                $attrName = $value->attribute->name;
                                $attrId   = $value->attribute->id;
                                if (!isset($attributeGroups[$attrName])) {
                                    $attributeGroups[$attrName] = [
                                        'id'     => $attrId,
                                        'values' => [],
                                    ];
                                }
                                $exists = collect($attributeGroups[$attrName]['values'])
                                            ->contains('id', $value->id);
                                if (!$exists) {
                                    $attributeGroups[$attrName]['values'][] = [
                                        'id'         => $value->id,
                                        'value'      => $value->value,
                                        'color_code' => $value->color_code,
                                    ];
                                }
                            }
                        }
                    @endphp

                    @foreach($attributeGroups as $attrName => $attrData)
                        <div class="mb-3">
                            <label class="form-label fw-500 d4">
                                {{ $attrName }}:
                                <span class="text-primary fw-normal"
                                    id="label-{{ Str::slug($attrName) }}">
                                    — নির্বাচন করুন
                                </span>
                            </label>
                            <div class="d-flex gap-2 flex-wrap"
                                data-attr-name="{{ Str::slug($attrName) }}">
                                @foreach($attrData['values'] as $value)
                                    <button type="button"
                                            class="btn btn-outline-secondary attr-btn"
                                            data-attr="{{ Str::slug($attrName) }}"
                                            data-value-id="{{ $value['id'] }}"
                                            data-value="{{ $value['value'] }}"
                                            @if($value['color_code'])
                                                style="background:{{ $value['color_code'] }};
                                                    border-color:{{ $value['color_code'] }};
                                                    width:36px;height:36px;padding:0;border-radius:50%"
                                                title="{{ $value['value'] }}"
                                            @endif
                                            onclick="selectAttribute(this)">
                                        @if(!$value['color_code'])
                                            {{ $value['value'] }}
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    {{-- Selected variant info --}}
                    <div id="variant-info" style="display:none"
                        class="variant-info anim-up ">

                        <div class="d-flex justify-content-between align-items-center ">

                            <div class="v-info-item">
                              <span class="v-info-label">Combination</span>
                              <span class="v-info-value" id="combination">—</span>
                            </div>
                            <div class="v-info-item">
                              <span class="v-info-label">Sale Price</span>
                              <span class="v-info-value" id="variant-price">—</span>
                            </div>
                            <div class="v-info-item">
                              <span class="v-info-label">Save</span>
                              <span class="v-info-value " id="save-tk">—</span>
                            </div>
                            <div class="v-info-item">
                              <span class="v-info-label">stock</span>
                              <span class="v-info-value" id="variant-stock-badge">—</span>
                            </div>
                        </div>
                    </div>

                </div>
            @endif

            {{-- Quantity + Add to Cart --}}
            <div class="d-flex gap-3 align-items-center mb-4 anim-up d4">
                <div class="d-flex align-items-center border rounded">
                    <button class="btn btn-light px-3"
                            onclick="changeQty(-1)">−</button>
                    <span id="qty-display" class="px-3 text-primary-c">1</span>
                    <button class="btn btn-light px-3"
                            onclick="changeQty(1)">+</button>
                </div>
                <input type="hidden" id="qty-input" value="1">

                <button id="add-cart-btn"
                        class="btn btn-primary px-5 py-2 anim-up"
                        onclick="handleAddToCart()"
                        {{ $product->total_stock <= 0 ? 'disabled' : '' }}>
                    @if($product->total_stock <= 0)
                        Stock নেই
                    @else
                        🛒 Cart-এ যোগ করুন
                    @endif
                </button>
            </div>

            {{-- Stock info --}}
            <p class="text-muted small d4">
                @if($product->total_stock > 0)
                    <i class="text-success-c fa-solid fa-check"></i> Stock আছে ({{ $product->total_stock }}টি)
                @else
                    ❌ Stock নেই
                @endif
            </p>

            {{-- Meta --}}
            @if($product->sku)
                <p class="text-muted small">SKU: {{ $product->sku }}</p>
            @endif

        </div>
    </div>

    <hr>

    {{-- ── Meta Accordion ── --}}
    <div class="meta-accordion">

      @if($product->description)
        <div class="meta-item">
          <button class="meta-trigger" onclick="toggleMeta(this)">
            Description
            <span class="meta-icon fa-solid fa-plus"></span>
          </button>
          <div class="meta-body">
            <div class="meta-content">
              {!! nl2br(e($product->description)) !!}
            </div>
          </div>
        </div>
      @endif

      <div class="meta-item">
        <button class="meta-trigger" onclick="toggleMeta(this)">
          Shipping & Delivery
          <span class="meta-icon fa-solid fa-plus"></span>
        </button>
        <div class="meta-body">
          <div class="meta-content">
            <strong style="color:var(--primary)">Dhaka</strong> — ১–২ কার্যদিবস (৳60)<br>
            <strong style="color:var(--primary)">Outside Dhaka</strong> — ৩–৫ কার্যদিবস (৳120)<br>
            <strong style="color:var(--success)">৳1,500+ order</strong> — বিনামূল্যে ডেলিভারি<br><br>
            অর্ডার সকাল ১১টার আগে দিলে একই দিনে পাঠানো হয়।
          </div>
        </div>
      </div>

      <div class="meta-item">
        <button class="meta-trigger" onclick="toggleMeta(this)">
          Returns & Exchange
          <span class="meta-icon fa-solid fa-plus"></span>
        </button>
        <div class="meta-body">
          <div class="meta-content">
            পণ্য পাওয়ার ৭ দিনের মধ্যে return করা যাবে। পণ্য unopened ও original অবস্থায় থাকতে হবে।
            Exchange-এর জন্য customer service-এ যোগাযোগ করুন।
          </div>
        </div>
      </div>

      @if($product->weight)
        <div class="meta-item">
          <button class="meta-trigger" onclick="toggleMeta(this)">
            Specifications
            <span class="meta-icon fa-solid fa-plus"></span>
          </button>
          <div class="meta-body">
            <div class="meta-content">
              @if($product->weight)
                <strong style="color:var(--primary)">Weight:</strong>
                {{ $product->weight }}g<br>
              @endif
              @if($product->brand)
                <strong style="color:var(--primary)">Brand:</strong>
                {{ $product->brand }}<br>
              @endif
              @if($product->sku)
                <strong style="color:var(--primary)">SKU:</strong>
                {{ $product->sku }}
              @endif
            </div>
          </div>
        </div>
      @endif

    </div>

    {{-- Related Products --}}
    @if($related->count())
        <div class="mt-5">
            <h4 class="mb-4">সম্পর্কিত পণ্য</h4>
            <div class="row g-3">
                @foreach($related as $i => $relProduct)
                    <div class="col-xl-3 col-md-6 anim-up d{{ $i+1 }}">
                        @include('frontend.component.product-card', ['product' => $relProduct])
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

@endsection

@push('scripts')
<script>

// ─────────────────────────────
// Variant data (PHP → JS)
// ─────────────────────────────
@php
    $variants = $product->activeVariants->map(function ($v) {
        return [
            'id' => $v->id,
            'price' => $v->price,
            'sale_price' => $v->sale_price,
            'stock' => $v->stock,
            'image' => $v->image,
            'attribute_values' => $v->attributeValues->pluck('id')->toArray(),
        ];
    });
@endphp

const allVariants = @json($variants);

// ─────────────────────────────
// State
// ─────────────────────────────
let selected = {};
let selectedVariantId = null;
let currentQty = 1;

// ─────────────────────────────
// Init
// ─────────────────────────────
document.addEventListener('DOMContentLoaded', () => {

    updateAvailability();
    updateVariantInfo();

});

// ─────────────────────────────
// Attribute select
// ─────────────────────────────
function selectAttribute(btn) {

    const attr    = btn.dataset.attr;
    const valueId = parseInt(btn.dataset.valueId);
    const value   = btn.dataset.value;

    // same button clicked = deselect
    if (selected[attr] === valueId) {

        delete selected[attr];

        btn.classList.remove('btn-primary', 'active');
        btn.classList.add('btn-outline-secondary');
        const label = document.getElementById('label-' + attr);

        if (label) {
            label.textContent = '— নির্বাচন করুন';
        }

    } else {

        // clear old selected
        document.querySelectorAll(`.attr-btn[data-attr="${attr}"]`)
            .forEach(b => {

                b.classList.remove('btn-primary', 'active');
                b.classList.add('btn-outline-secondary');


            });

        // set selected
        selected[attr] = valueId;

        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-primary', 'active');

        const label = document.getElementById('label-' + attr);

        if (label) {
            label.textContent = value;
        }
    }

    updateAvailability();
    updateVariantInfo();
}

// ─────────────────────────────
// Check available buttons
// ─────────────────────────────
function updateAvailability() {

    const attrGroups = document.querySelectorAll('[data-attr-name]');

    attrGroups.forEach(group => {

        const currentAttr = group.dataset.attrName;
        const buttons = group.querySelectorAll('.attr-btn');

        buttons.forEach(btn => {

            const valueId = parseInt(btn.dataset.valueId);

            const available = checkAvailability(
                currentAttr,
                valueId
            );

            // selected button সবসময় enabled থাকবে
            const isSelected = selected[currentAttr] === valueId;

            if (available || isSelected) {

                btn.disabled = false;

                btn.style.opacity = '1';
                btn.style.cursor = 'pointer';
                btn.style.textDecoration = 'none';

            } else {

                btn.disabled = true;

                btn.style.opacity = '0.35';
                btn.style.cursor = 'not-allowed';
                btn.style.textDecoration = 'line-through';

            }

        });

    });

}

// ─────────────────────────────
// Check if selecting this value
// still keeps any valid variant
// ─────────────────────────────
function checkAvailability(targetAttr, targetValueId) {

    // selected বাদে অন্য selected values
    const otherSelected = Object.entries(selected)
        .filter(([attr]) => attr !== targetAttr)
        .map(([, valueId]) => valueId);

    return allVariants.some(variant => {

        // target value থাকতে হবে
        if (
            !variant.attribute_values.includes(targetValueId)
        ) {
            return false;
        }

        // অন্য selected values থাকতে হবে
        if (
            !otherSelected.every(id =>
                variant.attribute_values.includes(id)
            )
        ) {
            return false;
        }

        // stock থাকতে হবে
        return variant.stock > 0;

    });

}

// ─────────────────────────────
// Update variant info
// ─────────────────────────────
    const HAS_VARIANTS = @json($product->has_variants);

    function updateVariantInfo() {

        if (!HAS_VARIANTS) {
            return;
        }

        const selectedIds = Object.values(selected);

        //main data top
        const priceMain     = document.getElementById('present-price');
        const originalPrice     = document.getElementById('original-price');
        const chipEl    = document.getElementById('commission');

        //varient card data
        const infoBox     = document.getElementById('variant-info');
        const addBtn      = document.getElementById('add-cart-btn');
        const priceEl     = document.getElementById('variant-price');
        const savePrice = document.getElementById('save-tk');
        const stockBadge  = document.getElementById('variant-stock-badge');

        // reset
        selectedVariantId = null;

        // nothing selected
        if (selectedIds.length === 0) {

            if (infoBox) {
                infoBox.style.display = 'none';
            }

            if (addBtn) {
                addBtn.disabled = true;
                addBtn.textContent = 'Variation নির্বাচন করুন';
            }

            return;
        }

        // ─────────────────────────
        // exact match
        // ─────────────────────────
        const exact = allVariants.find(v => {

            const a = [...v.attribute_values].sort();
            const b = [...selectedIds].sort();

            return JSON.stringify(a) === JSON.stringify(b);

        });

        // ─────────────────────────
        // partial match
        // ─────────────────────────
        const partial = allVariants.find(v =>

            selectedIds.every(id =>
                v.attribute_values.includes(id)
            )

        );

        const matched = exact || partial;

        // no match
        if (!matched ) {

            if (infoBox) {
                infoBox.style.display = 'none';
            }

            if (addBtn) {
                addBtn.disabled = true;
                addBtn.textContent = 'Combination নেই';
            }

            return;
        }

        
        // ─────────────────────────
        // update info
        // ─────────────────────────
        const price = matched.sale_price ?? matched.price;
        //বাংলার জন্য
        const priceStr = '৳' + price.toLocaleString('en-BD');

        if (priceEl) {
            priceEl.textContent ='৳ ' + price;
        }

        // combination
        document.getElementById('combination').textContent =
        Object.values(selected).map(id => {
            const btn = document.querySelector(`.attr-btn[data-value-id="${id}"]`);
            return btn?.dataset.value ?? id;
        }).join(' / ');

        if (savePrice) {

            if (matched.sale_price) {

                savePrice.textContent = '৳ ' + Number(matched.price - matched.sale_price);

            } else {

                savePrice.textContent = '';

            }
        }
        
        if (priceMain) {
            priceMain.textContent = '৳ ' + price;
        }
        if (originalPrice) {

            if (matched.sale_price) {

                originalPrice.textContent ='৳ ' + Number(matched.price);

            } else {

                originalPrice.textContent = '';

            }
        }

        if(chipEl){
            const pct = Math.round((1 - matched.sale_price / matched.price) * 100);
            chipEl.textContent   = pct + '% OFF';
            chipEl.style.display = '';
        }

        // stock badge
        if (stockBadge) {

            if (matched.stock > 10) {

                stockBadge.innerHTML =
                    '<span class="badge bg-success">Stock আছে</span>';

            } else if (matched.stock > 0) {

                stockBadge.innerHTML =
                    `<span class="badge bg-g6 text-dark">
                        মাত্র ${matched.stock}টি বাকি
                    </span>`;

            } else {

                stockBadge.innerHTML =
                    '<span class="badge bg-danger">Stock নেই</span>';

            }

        }

        // show info
        if (infoBox) {
            infoBox.style.display = 'block';
        }

        // image update
        if (matched.image) {

            const mainImg = document.getElementById('main-image');

            if (mainImg) {
                mainImg.src = '/storage/' + matched.image;
            }

        }

        // ─────────────────────────
        // exact variant selected
        // ─────────────────────────
        if (exact) {

            selectedVariantId = exact.id;

            if (addBtn) {

                addBtn.disabled = exact.stock <= 0;

                addBtn.textContent =
                    exact.stock > 0
                        ? '🛒 Cart-এ যোগ করুন'
                        : 'Stock নেই';

            }

        } else {

            // partial selected
            if (addBtn) {

                infoBox.style.display = 'none';
                addBtn.disabled = true;
                addBtn.textContent =
                    'এই Variation নেই';

            }

        }

    }

// ─────────────────────────────
// Quantity
// ─────────────────────────────
function changeQty(delta) {

    currentQty = Math.max(1, currentQty + delta);

    const qtyDisplay =
        document.getElementById('qty-display');

    if (qtyDisplay) {
        qtyDisplay.textContent = currentQty;
    }

}

// ─────────────────────────────
// Add to cart
// ─────────────────────────────
function handleAddToCart() {

    @if($product->has_variants)

        if (!selectedVariantId) {

            showToast(
                'সব Variation নির্বাচন করুন।',
                false
            );

            return;
        }

        addToCart(
            {{ $product->id }},
            selectedVariantId,
            currentQty
        );

    @else

        addToCart(
            {{ $product->id }},
            null,
            currentQty
        );

    @endif

}



// ── Accordion for description,delivery info zip/unzip ─────────────────────────────────────────────
function toggleMeta(trigger) {
  const body = trigger.nextElementSibling;
  const isOpen = body.classList.contains('open');

  // Close all
  document.querySelectorAll('.meta-body.open').forEach(b => b.classList.remove('open'));
  document.querySelectorAll('.meta-trigger.open').forEach(t => t.classList.remove('open'));

  if (!isOpen) {
    body.classList.add('open');
    trigger.classList.add('open');
  }
}

</script>
@endpush