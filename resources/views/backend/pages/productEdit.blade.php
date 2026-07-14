@extends('backend.layouts.masterLayout')
@section('title', 'Edit: '.$product->name)
@section('content')
<div class="container py-4" style="max-width:900px">

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.products.index') }}"
           class="btn btn-outline-secondary btn-sm">← ফিরুন</a>
        <h4 class="mb-0">সম্পাদনা: {{ $product->name }}</h4>
    </div>

    {{-- Messages --}}

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ══════════════════════════════
        ছবি
    ══════════════════════════════ --}}

    <div class="card mb-4">
        <div class="card-header fw-500">ছবি</div>
        <div class="card-body">
            <label class="form-label">সকল ছবি</label>
            @if($product->images->count())
                <div class="d-flex flex-wrap gap-2 mb-3">
                    @foreach($product->images as $image)
                        <div style="position:relative">
                            <img src="{{ asset('storage/'.$image->image) }}"
                                    style="width:80px;height:80px;object-fit:cover;
                                        border-radius:8px;
                                        border:{{ $image->is_primary ? '2px solid #0d6efd' : '1px solid #dee2e6' }}">

                            @if($image->is_primary)
                                <span style="position:absolute;bottom:3px;left:3px;
                                                font-size:9px;background:rgba(13,110,253,.85);
                                                color:#fff;padding:1px 4px;border-radius:3px">
                                    মূল
                                </span>
                            @else
                                <form method="POST"
                                                action="{{ route('admin.products.images.primary', $image) }}"
                                                style="position:absolute;top:2px;right:24px">
                                                @csrf
                                                <button class="btn btn-xs" type="submit"
                                                        style="padding:1px 4px;font-size:10px;
                                                            background:rgba(255,255,255,0.8)"
                                                        title="মূল ছবি করুন">★</button>
                                            </form>
                            @endif

                            <form method="POST"
                                    action="{{ route('admin.products.images.destroy', $image) }}"
                                    onsubmit="return confirm('ছবি মুছবেন?')"
                                    style="position:absolute;top:3px;right:3px">
                                @csrf @method('DELETE')
                                <button style="font-size:10px;padding:0 4px;
                                                border:none;border-radius:3px;
                                                background:rgba(255,255,255,.85)">✕</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════
        মূল তথ্য
    ══════════════════════════════ --}}
    <div class="container border border-2 py-4" >
        <form action="{{ route('admin.products.update', $product) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
            <div class="row g-2">
                
                {{-- বাম কলাম: মূল তথ্য --}}
                <div class="col-md-8">

                    <div class="card mb-4">
                        <div class="card-header fw-500">মূল তথ্য</div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">নাম *</label>
                                <input type="text" name="name"
                                    value="{{ old('name', $product->name) }}"
                                    class="form-control @error('name') is-invalid @enderror">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">সংক্ষিপ্ত বিবরণ</label>
                                <textarea name="short_description" rows="2" class="form-control">{{ old('short_description', $product->short_description) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">বিস্তারিত বিবরণ</label>
                                <textarea name="description" rows="4" class="form-control">{{ old('description', $product->description) }}</textarea>
                            </div>

                        </div>
                    </div>

                    {{-- দাম ও Stock --}}
                    <div class="card mb-4">
                        <div class="card-header fw-500">দাম ও Stock</div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">মূল দাম *</label>
                                    <input type="number" name="base_price" step="0.01"
                                        value="{{ old('base_price', $product->base_price) }}"
                                        class="form-control @error('base_price') is-invalid @enderror">
                                    @error('base_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">ছাড়ের দাম</label>
                                    <input type="number" name="sale_price" step="0.01"
                                        value="{{ old('sale_price', $product->sale_price) }}"
                                        class="form-control">
                                </div>
                                @if(!$product->has_variants)
                                    <div class="col-md-4">
                                        <label class="form-label">Stock</label>
                                        <input type="number" name="stock"
                                            value="{{ old('stock', $product->stock) }}"
                                            class="form-control">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ছবি --}}
                    <div class="card mb-4">
                        <div class="card-header fw-500">নতুন ছবি যোগ</div>
                        <div class="card-body">

                            <label class="form-label">নতুন ছবি যোগ করুন</label>
                            <input type="file" name="images[]" multiple
                                accept="image/*" class="form-control"
                                onchange="previewImages(this)">
                            <div id="image-preview" class="d-flex flex-wrap gap-2 mt-2"></div>
                        </div>
                    </div>

                </div>
                {{-- ডান কলাম: Category ও অন্যান্য --}}
                <div class="col-md-4">

                        <div class="card mb-4">
                            <div class="card-header fw-500">Category</div>
                            <div class="card-body">
                                <select name="category_id" class="form-select">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                            {{ str_repeat('— ', $category->level - 1) }}{{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header fw-500">অন্যান্য</div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Brand</label>
                                    <input type="text" name="brand"
                                        value="{{ $product->brand }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">SKU</label>
                                    <input type="text" name="sku"
                                        value="{{ $product->sku }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">ওজন (গ্রাম)</label>
                                    <input type="text" name="weight"
                                        value="{{ $product->weight }}" class="form-control">
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input" type="checkbox"
                                        name="is_active" value="1"
                                        {{ $product->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label">সক্রিয়</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_featured" value="0">
                                    <input class="form-check-input" type="checkbox"
                                        name="is_featured" value="1"
                                        {{ $product->is_featured ? 'checked' : '' }}>
                                    <label class="form-check-label">Featured</label>
                                </div>
                            </div>
                        </div>

                </div>

                <button type="submit" class="btn btn-primary">
                    মূল তথ্য আপডেট করুন
                </button>

            </div>
        </form>
    </div>

        {{-- ══════════════════════════════
                Variant Management
        ══════════════════════════════ --}}

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-500">Variant ব্যবস্থাপনা</span>
            @if($product->variants->count())
                <span class="badge bg-info">
                    {{ $product->variants->count() }}টি variant আছে
                </span>
            @endif
        </div>
        <div class="card-body">

            {{-- ── বিদ্যমান Variants ── --}}
            @if($product->variants->count())
                <h6 class="text-muted mb-3">বিদ্যমান Variants</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width:80px">ছবি</th>
                                <th>Combination</th>
                                <th style="width:120px">দাম</th>
                                <th style="width:120px">ছাড়ের দাম</th>
                                <th style="width:90px">Stock</th>
                                <th style="width:110px">SKU</th>
                                <th style="width:80px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->variants as $variant)
                                <tr>
                                    {{-- ছবি column --}}
                                    <td>
                                        <div style="position:relative;display:inline-block">

                                            {{-- ছবি দেখানো --}}
                                            @if($variant->image)
                                                <img src="{{ asset('storage/'.$variant->image) }}"
                                                    style="width:56px;height:56px;object-fit:cover;
                                                            border-radius:6px;border:1px solid #dee2e6"
                                                    id="variant-img-{{ $variant->id }}">

                                                {{-- ছবি মুছার বোতাম --}}
                                                <form method="POST"
                                                    action="{{ route('admin.products.variants.image.destroy', [$product, $variant]) }}"
                                                    onsubmit="return confirm('ছবি মুছবেন?')"
                                                    style="position:absolute;top:2px;right:2px">
                                                    @csrf @method('DELETE')
                                                    <button style="width:18px;height:18px;padding:0;font-size:10px;
                                                                border:none;border-radius:50%;line-height:1;
                                                                background:rgba(220,53,69,.85);color:#fff">
                                                        ✕
                                                    </button>
                                                </form>

                                            @else
                                                {{-- ছবি নেই placeholder --}}
                                                <div style="width:56px;height:56px;border-radius:6px;
                                                            border:1px dashed #dee2e6;display:flex;
                                                            align-items:center;justify-content:center;
                                                            color:#aaa;font-size:10px;text-align:center;
                                                            cursor:pointer"
                                                    onclick="document.getElementById('img-upload-{{ $variant->id }}').click()"
                                                    title="ছবি যোগ করুন">
                                                    ছবি<br>যোগ
                                                </div>
                                            @endif

                                            {{-- Hidden upload trigger --}}
                                            <form method="POST"
                                                action="{{ route('admin.products.variants.image', [$product, $variant]) }}"
                                                enctype="multipart/form-data"
                                                id="img-form-{{ $variant->id }}">
                                                @csrf
                                                <input type="file"
                                                    id="img-upload-{{ $variant->id }}"
                                                    name="image"
                                                    accept="image/*"
                                                    style="display:none"
                                                    onchange="previewVariantImage(this, {{ $variant->id }})">
                                            </form>

                                        </div>

                                        {{-- ছবি থাকলে পরিবর্তনের option --}}
                                        @if($variant->image)
                                            <div style="margin-top:3px;text-align:center">
                                                <span style="font-size:10px;color:#6c757d;cursor:pointer"
                                                    onclick="document.getElementById('img-upload-{{ $variant->id }}').click()">
                                                    পরিবর্তন
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    {{-- Combination label --}}
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $variant->attributeValues->pluck('value')->join(' / ') }}
                                        </span>
                                    </td>

                                    {{-- Inline edit form --}}
                                    <form method="POST"
                                            action="{{ route('admin.products.variants.update', [$product, $variant]) }}">
                                        @csrf @method('PUT')
                                        <td>
                                            <input type="number" name="price"
                                                    value="{{ $variant->price }}"
                                                    step="0.01"
                                                    class="form-control form-control-sm">
                                        </td>
                                        <td>
                                            <input type="number" name="sale_price"
                                                    value="{{ $variant->sale_price }}"
                                                    step="0.01"
                                                    class="form-control form-control-sm">
                                        </td>
                                        <td>
                                            <input type="number" name="stock"
                                                    value="{{ $variant->stock }}"
                                                    class="form-control form-control-sm">
                                        </td>
                                        <td>
                                            <input type="text" name="sku"
                                                    value="{{ $variant->sku }}"
                                                    class="form-control form-control-sm">
                                        </td>
                                        <td>
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-primary w-100 mb-1">
                                                সংরক্ষণ
                                            </button>
                                    </form>

                                    {{-- Delete form আলাদা --}}
                                            <form method="POST"
                                                    action="{{ route('admin.products.variants.destroy', [$product, $variant]) }}"
                                                    onsubmit="return confirm('এই variant মুছবেন?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger w-100">
                                                    মুছুন
                                                </button>
                                            </form>
                                        </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-secondary mb-4">
                    এখনো কোনো variant নেই।
                </div>
            @endif

            {{-- ── নতুন Variant যোগ ── --}}
            <h6 class="text-muted mb-3">নতুন Variant যোগ করুন</h6>

            <form method="POST"
                    action="{{ route('admin.products.variants.store', $product) }}"
                    id="add-variant-form">
                @csrf

                {{-- Attribute নির্বাচন --}}
                <div class="mb-3">
                    <label class="form-label">Attribute নির্বাচন করুন</label>
                    <div class="d-flex gap-3 flex-wrap">
                        @foreach($attributes as $attribute)
                            <div class="form-check">
                                <input class="form-check-input attribute-check"
                                        type="checkbox"
                                        value="{{ $attribute->id }}"
                                        id="attr_{{ $attribute->id }}"
                                        onchange="generateVariants()">
                                <label class="form-check-label"
                                        for="attr_{{ $attribute->id }}">
                                    {{ $attribute->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div id="attribute-values-section"></div>

                <div id="variants-table-section" style="display:none">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Combination</th>
                                    <th>দাম *</th>
                                    <th>ছাড়ের দাম</th>
                                    <th>Stock *</th>
                                    <th>SKU</th>
                                </tr>
                            </thead>
                            <tbody id="variants-tbody"></tbody>
                        </table>
                    </div>
                    <button type="submit" class="btn btn-success mt-2">
                        নতুন Variant সংরক্ষণ করুন
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<script>
const attributeData = @json($attributes);

function generateVariants() {
    const checked = [...document.querySelectorAll('.attribute-check:checked')];
    const valuesSection = document.getElementById('attribute-values-section');

    if (checked.length === 0) {
        valuesSection.innerHTML = '';
        document.getElementById('variants-table-section').style.display = 'none';
        return;
    }

    let html = '<div class="row g-2 mb-3">';
    checked.forEach(cb => {
        const attr = attributeData.find(a => a.id == cb.value);
        html += `<div class="col-md-6">
            <label class="form-label small">${attr.name}:</label>
            <div class="d-flex flex-wrap gap-1">`;
        attr.values.forEach(v => {
            html += `<div class="form-check form-check-inline">
                <input class="form-check-input value-check" type="checkbox"
                       value="${v.id}" data-attr="${attr.id}"
                       id="val_${v.id}" onchange="buildVariantTable()">
                <label class="form-check-label" for="val_${v.id}">${v.value}</label>
            </div>`;
        });
        html += `</div></div>`;
    });
    html += '</div>';
    valuesSection.innerHTML = html;
    buildVariantTable();
}

//ভেরিয়েন্ট এর টেবিল তৈরির জন্য
function buildVariantTable() {
    const attrGroups = {};
    document.querySelectorAll('.value-check:checked').forEach(cb => {
        const attr = cb.dataset.attr;
        if (!attrGroups[attr]) attrGroups[attr] = [];
        attrGroups[attr].push({ id: cb.value, label: cb.nextElementSibling.textContent.trim() });
    });

    const groups = Object.values(attrGroups);
    if (groups.length === 0) {
        document.getElementById('variants-table-section').style.display = 'none';
        return;
    }

    const combinations = groups.reduce((acc, group) => {
        return acc.flatMap(a => group.map(b => [...a, b]));
    }, [[]]);

    let rows = '';
    combinations.forEach((combo, i) => {
        const label        = combo.map(c => c.label).join(' / ');
        const hiddenInputs = combo.map(c =>
            `<input type="hidden"
                    name="variants[${i}][attribute_value_ids][]"
                    value="${c.id}">`
        ).join('');

        rows += `<tr>
            <td>${label}${hiddenInputs}</td>
            <td><input type="number" name="variants[${i}][price]"
                       step="0.01" class="form-control form-control-sm"></td>
            <td><input type="number" name="variants[${i}][sale_price]"
                       step="0.01" class="form-control form-control-sm"></td>
            <td><input type="number" name="variants[${i}][stock]"
                       value="0" class="form-control form-control-sm"></td>
            <td><input type="text" name="variants[${i}][sku]"
                       class="form-control form-control-sm"></td>
        </tr>`;
    });

    document.getElementById('variants-tbody').innerHTML = rows;
    document.getElementById('variants-table-section').style.display = 'block';
}

//ভেরিয়েন্ট এর ছবি প্রিভিউ
function previewVariantImage(input, variantId) {
    if (!input.files || !input.files[0]) return;

    const file    = input.files[0];
    const imgEl   = document.getElementById('variant-img-' + variantId);

    // preview দেখাও
    if (imgEl) {
        imgEl.src = URL.createObjectURL(file);
    }

    // confirm করে submit
    if (confirm('এই ছবি আপলোড করবেন?')) {
        document.getElementById('img-form-' + variantId).submit();
    } else {
        input.value = '';
        if (imgEl) imgEl.src = imgEl.dataset.original;
    }
}

//প্রডাক্ট এর ছবি প্রিভিউ
function previewImages(input) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    [...input.files].slice(0, 5).forEach(file => {
        const img = document.createElement('img');
        img.src   = URL.createObjectURL(file);
        img.style = 'width:60px;height:60px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6';
        preview.appendChild(img);
    });
}
</script>
@endsection