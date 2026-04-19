@extends('frontend/layouts/masterLayout')

@section('content')
<div class="container mt-5 py-4">
<div class="row g-4">

    {{-- বাম: ছবি --}}
    <div class="col-md-5">

        {{-- মূল ছবি --}}
        <div class="border rounded mb-2 overflow-hidden"
             style="aspect-ratio:1; background:#f8f8f8">
            <img id="main-image"
                 src="{{ $product->primaryImage
                    ? asset('storage/'.$product->primaryImage->image)
                    : asset('images/no-image.png') }}"
                 alt="{{ $product->name }}"
                 style="width:100%; height:100%; object-fit:contain">
        </div>

        {{-- Thumbnail --}}
        @if($product->images->count() > 1)
            <div class="d-flex gap-2 flex-wrap">
                @foreach($product->images as $image)
                    <img src="{{ asset('storage/'.$image->image) }}"
                         alt=""
                         onclick="changeImage('{{ asset('storage/'.$image->image) }}')"
                         style="width:70px; height:70px; object-fit:cover;
                                border-radius:6px; cursor:pointer;
                                border:2px solid {{ $image->is_primary ? '#0d6efd' : '#dee2e6' }}"
                         class="thumb-img">
                @endforeach
            </div>
        @endif

    </div>

    {{-- ডান: তথ্য --}}
    <div class="col-md-7">

        {{-- Category breadcrumb --}}
        <nav class="mb-2">
            <small class="text-muted">
                {{ $product->category->name }}
            </small>
        </nav>

        {{-- নাম --}}
        <h4 class="mb-1">{{ $product->name }}</h4>

        @if($product->brand)
            <p class="text-muted small mb-2">Brand: {{ $product->brand }}</p>
        @endif

        {{-- দাম --}}
        <div class="mb-3" id="price-section">
            @if($product->has_variants)
                <span class="fs-4 fw-500 text-primary" id="display-price">
                    দাম নির্বাচন করুন
                </span>
            @else
                @if($product->sale_price)
                    <span class="fs-4 fw-500 text-danger">
                        ৳{{ number_format($product->sale_price, 0) }}
                    </span>
                    <span class="text-muted text-decoration-line-through ms-2">
                        ৳{{ number_format($product->base_price, 0) }}
                    </span>
                    <span class="badge bg-danger ms-1">
                        {{ round((($product->base_price - $product->sale_price) / $product->base_price) * 100) }}% ছাড়
                    </span>
                @else
                    <span class="fs-4 fw-500 text-primary">
                        ৳{{ number_format($product->base_price, 0) }}
                    </span>
                @endif
            @endif
        </div>

        {{-- Variant নির্বাচন --}}
        @if($product->has_variants && count($attributeGroups))
            <div id="variant-section" class="mb-3">
                @foreach($attributeGroups as $attrName => $attr)
                    <div class="mb-3">
                        <p class="mb-1 fw-500 small">
                            {{ $attrName }}:
                            <span class="text-primary fw-400"
                                  id="selected-{{ Str::slug($attrName) }}">
                                নির্বাচন করুন
                            </span>
                        </p>

                        <div class="d-flex flex-wrap gap-2">
                            @foreach($attr['values'] as $value)

                                @if($attr['type'] === 'color')
                                    {{-- Color swatch --}}
                                    <button type="button"
                                            class="variant-btn color-btn"
                                            data-attr="{{ Str::slug($attrName) }}"
                                            data-value-id="{{ $value['id'] }}"
                                            data-label="{{ $value['value'] }}"
                                            title="{{ $value['value'] }}"
                                            style="width:34px; height:34px; border-radius:50%;
                                                   background:{{ $value['color_code'] ?? '#ccc' }};
                                                   border:2px solid #dee2e6; cursor:pointer">
                                    </button>

                                @else
                                    {{-- Text button --}}
                                    <button type="button"
                                            class="variant-btn btn btn-outline-secondary btn-sm"
                                            data-attr="{{ Str::slug($attrName) }}"
                                            data-value-id="{{ $value['id'] }}"
                                            data-label="{{ $value['value'] }}">
                                        {{ $value['value'] }}
                                    </button>
                                @endif

                            @endforeach
                        </div>
                    </div>
                @endforeach

                {{-- নির্বাচিত variant info --}}
                <div id="variant-info" class="alert alert-light py-2 small"
                     style="display:none">
                    <span id="variant-label"></span> —
                    Stock: <span id="variant-stock"></span>
                </div>
            </div>
        @endif

        {{-- Stock --}}
        @if(!$product->has_variants)
            <p class="mb-3">
                @if($product->stock > 0)
                    <span class="badge bg-success">স্টকে আছে ({{ $product->stock }}টি)</span>
                @else
                    <span class="badge bg-danger">স্টক নেই</span>
                @endif
            </p>
        @endif

        {{-- পরিমাণ + Cart --}}
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="d-flex align-items-center border rounded overflow-hidden">
                <button class="btn btn-light px-3"
                        onclick="changeQty(-1)">−</button>
                <input type="number" id="qty" value="1" min="1"
                       style="width:50px; border:none; text-align:center"
                       class="form-control border-0 text-center">
                <button class="btn btn-light px-3"
                        onclick="changeQty(1)">+</button>
            </div>

            <button id="add-to-cart"
                    class="btn btn-primary px-4"
                    onclick="addToCart()">
                কার্টে যোগ করুন
            </button>
        </div>

        {{-- সংক্ষিপ্ত বিবরণ --}}
        @if($product->short_description)
            <p class="text-muted">{{ $product->short_description }}</p>
        @endif

        {{-- Meta info --}}
        <table class="table table-sm table-borderless"
               style="width:auto; font-size:13px">
            @if($product->sku)
                <tr>
                    <td class="text-muted pe-3">SKU:</td>
                    <td>{{ $product->sku }}</td>
                </tr>
            @endif
            @if($product->weight)
                <tr>
                    <td class="text-muted pe-3">ওজন:</td>
                    <td>{{ $product->weight }}g</td>
                </tr>
            @endif
            <tr>
                <td class="text-muted pe-3">Category:</td>
                <td>{{ $product->category->name }}</td>
            </tr>
        </table>

    </div>
</div>

{{-- বিস্তারিত বিবরণ --}}
@if($product->description)
    <div class="card mt-4">
        <div class="card-header">বিস্তারিত বিবরণ</div>
        <div class="card-body">
            {!! nl2br(e($product->description)) !!}
        </div>
    </div>
@endif

</div>

{{-- Variant data JS-এ পাঠাও --}}
<script>
const variantMap      = @json($variantMap);
const selectedValues  = {};    // { 'color': 5, 'size': 3 }
let   currentVariant  = null;

// variant button ক্লিক
document.querySelectorAll('.variant-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const attr  = this.dataset.attr;
        const valId = parseInt(this.dataset.valueId);
        const label = this.dataset.label;

        // একই attr-এর আগের selection সরাও
        document.querySelectorAll(`[data-attr="${attr}"]`).forEach(b => {
            b.style.borderColor = '#dee2e6';
            b.classList.remove('active', 'btn-primary');
            b.classList.add('btn-outline-secondary');
        });

        // এটা select করো
        this.style.borderColor = '#0d6efd';
        this.classList.add('active');
        if (this.classList.contains('btn-outline-secondary')) {
            this.classList.remove('btn-outline-secondary');
            this.classList.add('btn-primary');
        }

        selectedValues[attr] = valId;
        document.getElementById('selected-' + attr).textContent = label;

        findVariant();
    });
});

function findVariant() {
    const ids = Object.values(selectedValues).sort().join('-');
    currentVariant = variantMap[ids] || null;

    if (currentVariant) {
        // দাম আপডেট
        let priceHtml = '';
        if (currentVariant.sale_price) {
            const discount = Math.round(
                ((currentVariant.price - currentVariant.sale_price) / currentVariant.price) * 100
            );
            priceHtml = `
                <span class="fs-4 fw-500 text-danger">
                    ৳${currentVariant.sale_price.toLocaleString('bn-BD')}
                </span>
                <span class="text-muted text-decoration-line-through ms-2">
                    ৳${currentVariant.price.toLocaleString('bn-BD')}
                </span>
                <span class="badge bg-danger ms-1">${discount}% ছাড়</span>`;
        } else {
            priceHtml = `
                <span class="fs-4 fw-500 text-primary">
                    ৳${currentVariant.price.toLocaleString('bn-BD')}
                </span>`;
        }
        document.getElementById('display-price').outerHTML =
            `<div id="display-price">${priceHtml}</div>`;

        // variant ছবি থাকলে দেখাও
        if (currentVariant.image) {
            document.getElementById('main-image').src = currentVariant.image;
        }

        // stock info
        const info  = document.getElementById('variant-info');
        info.style.display = 'block';
        document.getElementById('variant-label').textContent = currentVariant.label;
        document.getElementById('variant-stock').textContent =
            currentVariant.stock > 0
                ? currentVariant.stock + 'টি আছে'
                : 'স্টক নেই';

        info.className = currentVariant.stock > 0
            ? 'alert alert-success py-2 small'
            : 'alert alert-danger py-2 small';

        // qty max সেট
        document.getElementById('qty').max = currentVariant.stock;

        // cart button
        document.getElementById('add-to-cart').disabled = currentVariant.stock <= 0;
    }
}

function changeQty(delta) {
    const input = document.getElementById('qty');
    const max   = parseInt(input.max) || 999;
    let val     = parseInt(input.value) + delta;
    if (val < 1)   val = 1;
    if (val > max) val = max;
    input.value = val;
}

function changeImage(src) {
    document.getElementById('main-image').src = src;
    document.querySelectorAll('.thumb-img').forEach(img => {
        img.style.borderColor = img.src === src ? '#0d6efd' : '#dee2e6';
    });
}

function addToCart() {
    const qty       = document.getElementById('qty').value;
    const variantId = currentVariant?.id || null;

    @if($product->has_variants)
    if (!currentVariant) {
        alert('আগে variation নির্বাচন করুন।');
        return;
    }
    @endif

    fetch('{{-- route("cart.add") --}}', {
        method:  'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            product_id: {{ $product->id }},
            variant_id: variantId,
            quantity:   qty,
        }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // cart count আপডেট (navbar-এ থাকলে)
            const cartCount = document.getElementById('cart-count');
            if (cartCount) cartCount.textContent = data.cart_count;

            // সাময়িক সফলতার বার্তা
            const btn = document.getElementById('add-to-cart');
            btn.textContent = 'যোগ হয়েছে ✓';
            btn.classList.replace('btn-primary', 'btn-success');
            setTimeout(() => {
                btn.textContent = 'কার্টে যোগ করুন';
                btn.classList.replace('btn-success', 'btn-primary');
            }, 2000);
        }
    });
}
</script>
@endsection