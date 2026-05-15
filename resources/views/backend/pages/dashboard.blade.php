 @extends('backend/layouts/masterLayout')


@section('content')
<div class="container-fluid py-4 px-4">

    <h4 class="mb-4">Dashboard</h4>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">মোট Product</div>
                    <div class="fs-3 fw-bold">{{ $stats['total_products'] }}</div>
                    <div class="text-success small">
                        {{ $stats['active_products'] }}টি সক্রিয়
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">মোট Customer</div>
                    <div class="fs-3 fw-bold">{{ $stats['total_users'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">কম Stock</div>
                    <div class="fs-3 fw-bold text-danger">{{ $stats['low_stock'] }}</div>
                    <div class="text-danger small">মনোযোগ প্রয়োজন</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">মোট Order</div>
                    <div class="fs-3 fw-bold">—</div>
                    <div class="text-muted small">শীঘ্রই আসছে</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent fw-500">দ্রুত কাজ</div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.products.create') }}"
                       class="list-group-item list-group-item-action">
                        + নতুন Product যোগ করুন
                    </a>
                    <a href="{{ route('admin.categories.create') }}"
                       class="list-group-item list-group-item-action">
                        + নতুন Category যোগ করুন
                    </a>
                    <a href="{{ route('admin.attributes.index') }}"
                       class="list-group-item list-group-item-action">
                        Attributes পরিচালনা
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection