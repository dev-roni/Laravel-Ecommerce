@extends('backend/layouts/masterLayout')
@section('title', 'Products')
@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">সকল Product</h4>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            + নতুন Product
        </a>
    </div>

    {{-- Messages --}}

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Filter --}}
    <div class="card mb-4">
        <div class="card-body py-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="search"
                           value="{{ request('search') }}"
                           placeholder="নাম বা SKU দিয়ে খুঁজুন"
                           class="form-control">
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-select">
                        <option value="">সব Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ str_repeat('— ', $cat->level - 1) }}{{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">সব অবস্থা</option>
                        <option value="active"
                            {{ request('status') === 'active' ? 'selected' : '' }}>
                            সক্রিয়
                        </option>
                        <option value="inactive"
                            {{ request('status') === 'inactive' ? 'selected' : '' }}>
                            নিষ্ক্রিয়
                        </option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-outline-primary">খুঁজুন</button>
                    <a href="{{ route('admin.products.index') }}"
                       class="btn btn-outline-secondary">রিসেট</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:70px">ছবি</th>
                        <th>নাম</th>
                        <th>Category</th>
                        <th>দাম</th>
                        <th>Stock</th>
                        <th style="width:100px">Variant</th>
                        <th style="width:90px">অবস্থা</th>
                        <th style="width:130px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            {{-- ছবি --}}
                            <td>
                                <a href="{{ route('admin.products.show', $product) }}">
                                    @if($product->primaryImage)
                                        <img src="{{ asset('storage/'.$product->primaryImage->image) }}"
                                            alt="{{ $product->name }}"
                                            style="width:50px;height:50px;object-fit:cover;
                                                    border-radius:6px">
                                    @else
                                        <div style="width:50px;height:50px;border-radius:6px;
                                                    background:#f1f1f1;display:flex;
                                                    align-items:center;justify-content:center;
                                                    color:#aaa;font-size:20px">
                                            ?
                                        </div>
                                    @endif
                                </a>
                            </td>

                            {{-- নাম --}}
                            <td>
                                <a href="{{ route('admin.products.show', $product) }}"
                                   class="fw-500 text-decoration-none">
                                    {{ $product->name }}
                                </a>
                                @if($product->sku)
                                    <br>
                                    <small class="text-muted">SKU: {{ $product->sku }}</small>
                                @endif
                                @if($product->is_featured)
                                    <span class="badge bg-warning text-dark ms-1">Featured</span>
                                @endif
                            </td>

                            {{-- Category --}}
                            <td>
                                <span class="text-muted small">
                                    {{ $product->category->name ?? '—' }}
                                </span>
                            </td>

                            {{-- দাম --}}
                            <td>
                                @if($product->sale_price)
                                    <span class="text-danger fw-500">
                                        ৳{{ number_format($product->sale_price) }}
                                    </span>
                                    <br>
                                    <small class="text-muted text-decoration-line-through">
                                        ৳{{ number_format($product->base_price) }}
                                    </small>
                                @else
                                    ৳{{ number_format($product->base_price) }}
                                @endif
                            </td>

                            {{-- Stock --}}
                            <td>
                                @php $stock = $product->total_stock @endphp
                                <span class="{{ $stock <= 5 ? 'text-danger fw-500' : '' }}">
                                    {{ $stock }}
                                </span>
                                @if($stock <= 5)
                                    <br><small class="text-danger">কম আছে</small>
                                @endif
                            </td>

                            {{-- Variant --}}
                            <td class="text-center">
                                @if($product->has_variants)
                                    <span class="badge bg-info">
                                        {{ $product->variants->count() }}টি
                                    </span>
                                @else
                                    <span class="text-muted small">নেই</span>
                                @endif
                            </td>

                            {{-- Active toggle --}}
                            <td>
                                <form method="POST"
                                      action="{{ route('admin.products.toggle', $product) }}">
                                    @csrf
                                    <button type="submit"
                                            class="badge border-0
                                            {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $product->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                    </button>
                                </form>
                            </td>

                            {{-- Action --}}
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.products.edit', $product) }}" title="Edit"
                                       class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>

                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Delete" 
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteProductModal{{ $product->id }}"
                                        data-id="{{ $product->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- product Delete Modal -->
                        <div class="modal fade" id="deleteProductModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h6 class="modal-title">ক্যাটাগরি মুছে ফেলুন</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <div class="modal-body text-center">
                                            <p>আপনি কি নিশ্চিত যে আপনি এই প্রডাক্ট মুছে ফেলতে চান?</p>
                                            <p class="text-danger">এই কাজটি পূর্বাবস্থায় ফেরানো যাবে না।</p>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                                            <button type="submit" name="submit" class="btn btn-danger">মুছে ফেলুন</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                কোনো product নেই।
                                <a href="{{ route('admin.products.create') }}">
                                    এখনই তৈরি করুন
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="card-footer">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>
@endsection