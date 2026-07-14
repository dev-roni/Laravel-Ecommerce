@extends('backend.layouts.masterLayout')
@section('title', 'Product-Create')
@section('content')
<div class="container py-4" style="max-width:860px">
    <h4 class="mb-4">নতুন Product তৈরি করুন</h4>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        <div class="row g-4">

            {{-- বাম কলাম --}}
            <div class="col-md-8">

                {{-- মূল তথ্য --}}
                <div class="card mb-4">
                    <div class="card-header">মূল তথ্য</div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label">নাম <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                   value="{{ old('name') }}"
                                   class="form-control @error('name') is-invalid @enderror">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">সংক্ষিপ্ত বিবরণ</label>
                            <textarea name="short_description" rows="2"
                                      class="form-control">{{ old('short_description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">বিস্তারিত বিবরণ</label>
                            <textarea name="description" rows="5"
                                      class="form-control">{{ old('description') }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- দাম ও Stock --}}
                <div class="card mb-4">
                    <div class="card-header">দাম ও Stock</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">মূল দাম <span class="text-danger">*</span></label>
                                <input type="number" name="base_price" step="0.01"
                                       value="{{ old('base_price') }}"
                                       class="form-control @error('base_price') is-invalid @enderror">
                                @error('base_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">ছাড়ের দাম</label>
                                <input type="number" name="sale_price" step="0.01"
                                       value="{{ old('sale_price') }}"
                                       class="form-control @error('sale_price') is-invalid @enderror">
                                @error('sale_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4" id="stock-field">
                                <label class="form-label">Stock</label>
                                <input type="number" name="stock"
                                       value="{{ old('stock', 0) }}"
                                       class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Variants --}}
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Variation</span>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox"
                                   name="has_variants" id="has_variants" value="1"
                                   {{ old('has_variants') ? 'checked' : '' }}
                                   onchange="toggleVariants(this.checked)">
                            <label class="form-check-label" for="has_variants">
                                Variation আছে
                            </label>
                        </div>
                    </div>

                    <div class="card-body" id="variants-section"
                         style="{{ old('has_variants') ? '' : 'display:none' }}">

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
                                        <label class="form-check-label" for="attr_{{ $attribute->id }}">
                                            {{ $attribute->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Attribute Values --}}
                        <div id="attribute-values-section"></div>

                        {{-- Generated Variants Table --}}
                        <div id="variants-table-section" style="display:none">
                            <hr>
                            <p class="text-muted small mb-2">প্রতিটি combination-এর দাম ও stock দিন:</p>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Variation</th>
                                            <th style="width:140px">দাম <span class="text-danger">*</span></th>
                                            <th style="width:140px">ছাড়ের দাম</th>
                                            <th style="width:100px">Stock <span class="text-danger">*</span></th>
                                            <th style="width:130px">SKU</th>
                                        </tr>
                                    </thead>
                                    <tbody id="variants-tbody"></tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- ডান কলাম --}}
            <div class="col-md-4">

                {{-- Category --}}
                <div class="card mb-4">
                    <div class="card-header">Category</div>
                    <div class="card-body">
                        <select name="category_id"
                                class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">নির্বাচন করুন</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ str_repeat('— ', $category->level - 1) }}{{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- ছবি --}}
                <div class="card mb-4">
                    <div class="card-header">ছবি (সর্বোচ্চ ৫টি)</div>
                    <div class="card-body">
                        <input type="file" name="images[]" multiple accept="image/*"
                               class="form-control @error('images') is-invalid @enderror"
                               onchange="previewImages(this)">
                        @error('images') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div id="image-preview" class="d-flex flex-wrap gap-2 mt-2"></div>
                        <small class="text-muted">প্রথম ছবিটি মূল ছবি হিসেবে ব্যবহার হবে।</small>
                    </div>
                </div>

                {{-- অন্যান্য --}}
                <div class="card mb-4">
                    <div class="card-header">অন্যান্য</div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <input type="text" name="brand"
                                   value="{{ old('brand') }}" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">SKU</label>
                            <input type="text" name="sku"
                                   value="{{ old('sku') }}" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ওজন (গ্রাম)</label>
                            <input type="text" name="weight"
                                   value="{{ old('weight') }}" class="form-control">
                        </div>

                        <div class="form-check form-switch mb-2">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox"
                                   name="is_active" value="1" id="is_active"
                                   {{ old('is_active', '1') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">সক্রিয়</label>
                        </div>

                        <div class="form-check form-switch">
                            <input type="hidden" name="is_featured" value="0">
                            <input class="form-check-input" type="checkbox"
                                   name="is_featured" value="1" id="is_featured"
                                   {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">Featured</label>
                        </div>

                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    সংরক্ষণ করুন
                </button>

            </div>
        </div>
    </form>
</div>

<script>
// Attribute data PHP থেকে JS-এ
const attributeData = @json($attributes);

function toggleVariants(checked) {
    document.getElementById('variants-section').style.display = checked ? 'block' : 'none';
    document.getElementById('stock-field').style.display = checked ? 'none' : 'block';
}

function generateVariants() {
    const checked = [...document.querySelectorAll('.attribute-check:checked')];
    const valuesSection = document.getElementById('attribute-values-section');

    if (checked.length === 0) {
        valuesSection.innerHTML = '';
        document.getElementById('variants-table-section').style.display = 'none';
        return;
    }

    // নির্বাচিত attribute-এর values দেখাও
    let html = '<div class="row g-2 mb-3">';
    checked.forEach(cb => {
        const attr = attributeData.find(a => a.id == cb.value);
        html += `<div class="col-md-6">
            <label class="form-label small fw-500">${attr.name} এর মান:</label>
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

function buildVariantTable() {
    const attrGroups = {};
    document.querySelectorAll('.value-check:checked').forEach(cb => {
        const attr = cb.dataset.attr;
        if (!attrGroups[attr]) attrGroups[attr] = [];
        attrGroups[attr].push({ id: cb.value, label: cb.nextElementSibling.textContent });
    });

    const groups = Object.values(attrGroups);
    if (groups.length === 0) {
        document.getElementById('variants-table-section').style.display = 'none';
        return;
    }

    // Cartesian product — সব combination তৈরি
    const combinations = groups.reduce((acc, group) => {
        return acc.flatMap(a => group.map(b => [...a, b]));
    }, [[]]);

    let rows = '';
    combinations.forEach((combo, i) => {
        const label = combo.map(c => c.label).join(' / ');
        const ids   = combo.map(c => c.id);
        const hiddenInputs = ids.map(id =>
            `<input type="hidden" name="variants[${i}][attribute_value_ids][]" value="${id}">`
        ).join('');

        rows += `<tr>
            <td>${label}${hiddenInputs}</td>
            <td><input type="number" name="variants[${i}][price]" step="0.01"
                       class="form-control form-control-sm" placeholder="০.০০"></td>
            <td><input type="number" name="variants[${i}][sale_price]" step="0.01"
                       class="form-control form-control-sm" placeholder="০.০০"></td>
            <td><input type="number" name="variants[${i}][stock]"
                       class="form-control form-control-sm" value="0"></td>
            <td><input type="text" name="variants[${i}][sku]"
                       class="form-control form-control-sm" placeholder="ঐচ্ছিক"></td>
        </tr>`;
    });

    document.getElementById('variants-tbody').innerHTML = rows;
    document.getElementById('variants-table-section').style.display = 'block';
}

function previewImages(input) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    [...input.files].slice(0, 5).forEach((file, i) => {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.style = 'width:70px;height:70px;object-fit:cover;border-radius:6px;border:2px solid ' + (i===0?'#0d6efd':'#dee2e6');
        img.title = i === 0 ? 'মূল ছবি' : '';
        preview.appendChild(img);
    });
}

</script>
@endsection
