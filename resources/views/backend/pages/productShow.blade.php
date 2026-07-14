@extends('backend/layouts/masterLayout')
@section('title', $product->name)
@section('content')
<div class="container py-4" style="max-width:900px">

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.products.index') }}"
           class="btn btn-outline-secondary btn-sm">← ফিরুন</a>
        <h5 class="mb-0">{{ $product->name }}</h5>
        <a href="{{ route('admin.products.edit', $product) }}"
           class="btn btn-primary btn-sm ms-auto">সম্পাদনা করুন</a>
    </div>

    <div class="row g-4">

        {{-- বাম --}}
        <div class="col-md-7">

            {{-- ছবি --}}
            <div class="card mb-4">
                <div class="card-header">ছবি সমূহ</div>
                <div class="card-body">
                    @if($product->images->count())
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($product->images as $image)
                                <div style="position:relative">
                                    <img src="{{ asset('storage/'.$image->image)}}"
                                         style="width:90px;height:90px;object-fit:cover;
                                                border-radius:8px;
                                                border:{{ $image->is_primary ? '2px solid #0d6efd' : '1px solid #dee2e6' }}">
                                    @if($image->is_primary)
                                        <span class="badge bg-primary"
                                              style="position:absolute;bottom:4px;left:4px;
                                                     font-size:10px">মূল</span>
                                    @endif

                                    {{-- Primary set --}}
                                    @unless($image->is_primary)
                                        <form method="POST"
                                              action="{{ route('admin.products.images.primary', $image) }}"
                                              style="position:absolute;top:2px;right:24px">
                                            @csrf
                                            <button class="btn btn-xs" type="submit"
                                                    style="padding:1px 4px;font-size:10px;
                                                           background:rgba(255,255,255,0.8)"
                                                    title="মূল ছবি করুন">★</button>
                                        </form>
                                    @endunless

                                    {{-- Delete --}}
                                    <form method="POST"
                                          action="{{ route('admin.products.images.destroy', $image) }}"
                                          onsubmit="return confirm('ছবি মুছবেন?')"
                                          style="position:absolute;top:2px;right:2px">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-xs"
                                                style="padding:1px 4px;font-size:10px;
                                                       background:rgba(255,255,255,0.8)">✕</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">কোনো ছবি নেই।</p>
                    @endif
                </div>
            </div>

            {{-- Variants --}}
            @if($product->has_variants)
                <div class="card">
                    <div class="card-header">Variants</div>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Combination</th>
                                    <th>দাম</th>
                                    <th>Stock</th>
                                    <th>SKU</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->variants as $variant)
                                    <tr>
                                        <td>
                                            {{ $variant->Label }}
                                        </td>
                                        <td>
                                            ৳{{ number_format($variant->price) }}
                                            @if($variant->sale_price)
                                                <br>
                                                <small class="text-danger">
                                                    ৳{{ number_format($variant->sale_price) }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="{{ $variant->stock <= 5 ? 'text-danger' : '' }}">
                                                {{ $variant->stock }}
                                            </span>
                                        </td>
                                        <td class="text-muted small">
                                            {{ $variant->sku ?? '—' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        {{-- ডান --}}
        <div class="col-md-5">
            <div class="card mb-4">
                <div class="card-header">তথ্য</div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="text-muted">Category</td>
                            <td>{{ $product->category->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">মূল দাম</td>
                            <td>৳{{ number_format($product->base_price) }}</td>
                        </tr>
                        @if($product->sale_price)
                            <tr>
                                <td class="text-muted">ছাড়ের দাম</td>
                                <td class="text-danger">
                                    ৳{{ number_format($product->sale_price) }}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td class="text-muted">মোট Stock</td>
                            <td>{{ $product->total_stock }}</td>
                        </tr>
                        @if($product->brand)
                            <tr>
                                <td class="text-muted">Brand</td>
                                <td>{{ $product->brand }}</td>
                            </tr>
                        @endif
                        @if($product->sku)
                            <tr>
                                <td class="text-muted">SKU</td>
                                <td>{{ $product->sku }}</td>
                            </tr>
                        @endif
                        @if($product->weight)
                            <tr>
                                <td class="text-muted">ওজন</td>
                                <td>{{ $product->weight }}g</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="text-muted">অবস্থা</td>
                            <td>
                                <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $product->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Featured</td>
                            <td>
                                <span class="badge {{ $product->is_featured ? 'bg-warning text-dark' : 'bg-light text-dark' }}">
                                    {{ $product->is_featured ? 'হ্যাঁ' : 'না' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($product->short_description)
                <div class="card mb-4">
                    <div class="card-header">সংক্ষিপ্ত বিবরণ</div>
                    <div class="card-body">
                        <p class="mb-0">{{ $product->short_description }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
