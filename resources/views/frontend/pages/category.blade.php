{{-- resources/views/shop/category.blade.php --}}
@extends('frontend.layouts.masterLayout')
@section('title', $category->name)

@section('content')
<div class="container py-4">

    {{-- Breadcrumb --}}
    <nav class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('shop.index') }}">Home</a>
            </li>
            @foreach($breadcrumb as $crumb)
                @if($loop->last)
                    <li class="breadcrumb-item active">{{ $crumb->name }}</li>
                @else
                    <li class="breadcrumb-item">
                        <a href="{{ route('shop.category', $crumb->slug) }}">
                            {{ $crumb->name }}
                        </a>
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>

    <div class="row g-4">

        {{-- Sidebar Filter --}}
        <div class="col-md-3">

            {{-- Sub Categories --}}
            @if($subCategories->count())
                <div class="card mb-3">
                    <div class="card-header fw-500">Sub Category</div>
                    <div class="list-group list-group-flush">
                        @foreach($subCategories as $sub)
                            <a href="{{ route('shop.category', $sub->slug) }}"
                               class="list-group-item list-group-item-action
                               {{ request()->route('slug') === $sub->slug ? 'active' : '' }}">
                                {{ $sub->name }}
                                <span class="badge bg-secondary float-end">
                                    {{ $sub->products->count() }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Price Filter --}}
            <div class="card">
                <div class="card-header fw-500">দাম ফিল্টার</div>
                <div class="card-body">
                    <form method="GET">
                        <div class="mb-2">
                            <label class="form-label small">সর্বনিম্ন (৳)</label>
                            <input type="number" name="min_price"
                                   value="{{ request('min_price') }}"
                                   class="form-control form-control-sm"
                                   placeholder="০">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">সর্বোচ্চ (৳)</label>
                            <input type="number" name="max_price"
                                   value="{{ request('max_price') }}"
                                   class="form-control form-control-sm"
                                   placeholder="যেকোনো">
                        </div>
                        <button class="btn btn-primary btn-sm w-100">
                            ফিল্টার করুন
                        </button>
                        @if(request('min_price') || request('max_price'))
                            <a href="{{ route('shop.category', $category->slug) }}"
                               class="btn btn-outline-secondary btn-sm w-100 mt-1">
                                রিসেট
                            </a>
                        @endif
                    </form>
                </div>
            </div>

        </div>

        {{-- Products --}}
        <div class="col-md-9">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted small">
                    {{ $products->total() }}টি পণ্য পাওয়া গেছে
                </span>
                <select class="form-select form-select-sm w-auto"
                        onchange="window.location.href=this.value">
                    @foreach([
                        ''           => 'সর্বশেষ',
                        'price_asc'  => 'দাম: কম থেকে বেশি',
                        'price_desc' => 'দাম: বেশি থেকে কম',
                    ] as $val => $label)
                        <option value="{{ request()->fullUrlWithQuery(['sort' => $val]) }}"
                            {{ request('sort') === $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if($products->isEmpty())
                <div class="text-center py-5 text-muted">
                    এই category-তে কোনো পণ্য নেই।
                </div>
            @else
                <div class="row g-3">
                    @foreach($products as $i => $product)
                        <div class="col-xl-3 col-md-6 anim-up d{{ $i+1 }}">
                            @include('frontend.component.product-card', compact('product'))
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $products->withQueryString()->links() }}
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
