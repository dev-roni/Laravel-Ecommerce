 @extends('backend/layouts/masterLayout')



@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4 px-4">

    <h4 class="mb-4">Dashboard</h4>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        @foreach([
            ['label' => 'মোট Product',  'value' => $stats['total_products'],
             'sub'   => $stats['active_products'] . 'টি সক্রিয়', 'color' => 'primary'],
            ['label' => 'আজকের Order',  'value' => $stats['today_orders'],
             'sub'   => 'মোট: ' . $stats['total_orders'], 'color' => 'info'],
            ['label' => 'Pending Order', 'value' => $stats['pending_orders'],
             'sub'   => 'মনোযোগ প্রয়োজন', 'color' => 'warning'],
            ['label' => 'মোট Customer', 'value' => $stats['total_customers'],
             'sub'   => '', 'color' => 'success'],
            ['label' => 'আজকের Revenue', 'value' => '৳' . number_format($stats['today_revenue']),
             'sub'   => 'মোট: ৳' . number_format($stats['total_revenue']), 'color' => 'success'],
            ['label' => 'কম Stock',     'value' => $stats['low_stock'],
             'sub'   => 'মনোযোগ প্রয়োজন', 'color' => 'danger'],
        ] as $stat)
            <div class="col-md-4 col-lg-2">
                <div class="card border-0 shadow-sm h-100
                            border-start border-4 border-{{ $stat['color'] }}">
                    <div class="card-body">
                        <div class="text-muted small">{{ $stat['label'] }}</div>
                        <div class="fs-4 fw-bold mt-1">{{ $stat['value'] }}</div>
                        @if($stat['sub'])
                            <div class="text-muted" style="font-size:11px">
                                {{ $stat['sub'] }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">

        {{-- Recent Orders --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span class="fw-500">সাম্প্রতিক Orders</span>
                    <a href="{{ route('admin.orders.index') }}"
                       class="btn btn-sm btn-outline-primary">সব দেখুন</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order নং</th>
                                <th>Customer</th>
                                <th>মোট</th>
                                <th>Status</th>
                                <th>তারিখ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                           class="text-decoration-none fw-500">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ $order->shipping_name }}</td>
                                    <td>৳{{ number_format($order->total) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status_color }}">
                                            {{ $order->status_label }}
                                        </span>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $order->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header fw-500">দ্রুত কাজ</div>
                <div class="list-group list-group-flush">
                    @foreach([
                        ['route' => 'admin.products.create',   'label' => '+ নতুন Product'],
                        ['route' => 'admin.categories.create', 'label' => '+ নতুন Category'],
                        ['route' => 'admin.orders.index',      'label' => 'Orders দেখুন'],
                        ['route' => 'admin.products.index',    'label' => 'Products দেখুন'],
                    ] as $link)
                        <a href="{{ route($link['route']) }}"
                           class="list-group-item list-group-item-action">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>
@endsection