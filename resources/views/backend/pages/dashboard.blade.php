
@extends('backend.layouts.masterLayout')
@section('title', 'Dashboard')

@push('styles')
<style>
.stat-card {
    border: none;
    border-radius: 12px;
    border-left: 4px solid transparent;
    transition: transform .2s, box-shadow .2s;
}
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(10,37,64,.1); }
.stat-val { font-size: 1.8rem; font-weight: 700; color: #0A2540; line-height: 1.1; }
.stat-lbl { font-size: .75rem; color: #6B7280; text-transform: uppercase; letter-spacing: .06em; }
.stat-sub { font-size: .78rem; color: #6B7280; margin-top: .2rem; }
.chart-card { border: none; border-radius: 12px; box-shadow: 0 2px 16px rgba(10,37,64,.06); }
</style>
@endpush

@section('content')
<div class="container-fluid py-4 px-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Dashboard</h4>
        <a href="{{ route('admin.sales.report') }}" class="btn btn-outline-primary btn-sm">
            📊 বিস্তারিত Report
        </a>
    </div>

    {{-- ── Stats Grid ── --}}
    <div class="row g-3 mb-4">

        @foreach([
            ['label'=>'আজকের Revenue','value'=>'৳'.number_format($stats['today_revenue']),
             'sub'=>'আজকের Order: '.$stats['today_orders'].'টি','color'=>'#22C55E','icon'=>'💰'],
            ['label'=>'এই মাসের Revenue','value'=>'৳'.number_format($stats['this_month_rev']),
             'sub'=>'মোট Revenue: ৳'.number_format($stats['total_revenue']),'color'=>'#1DA1A8','icon'=>'📈'],
            ['label'=>'Pending Orders','value'=>$stats['pending_orders'].'টি',
             'sub'=>'মোট Order: '.$stats['total_orders'].'টি','color'=>'#FACC15','icon'=>'⏳'],
            ['label'=>'মোট Customer','value'=>number_format($stats['total_customers']),
             'sub'=>'Active Product: '.$stats['total_products'],'color'=>'#0A2540','icon'=>'👥'],
        ] as $s)
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card shadow-sm h-100"
                     style="border-left-color:{{ $s['color'] }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-lbl mb-1">{{ $s['label'] }}</div>
                                <div class="stat-val">{{ $s['value'] }}</div>
                                <div class="stat-sub">{{ $s['sub'] }}</div>
                            </div>
                            <div style="font-size:1.8rem;opacity:.4">{{ $s['icon'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </div>

    {{-- ── Charts Row ── --}}
    <div class="row g-4 mb-4">

        {{-- Revenue Chart --}}
        <div class="col-lg-8">
            <div class="card chart-card">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <span class="fw-600">Revenue (গত ১২ মাস)</span>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
        </div>

        {{-- Order Status Pie --}}
        <div class="col-lg-4">
            <div class="card chart-card h-100">
                <div class="card-header bg-transparent">
                    <span class="fw-600">Order Status</span>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="statusChart" height="220"></canvas>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-4">

        {{-- Top Products --}}
        <div class="col-lg-4">
            <div class="card chart-card">
                <div class="card-header bg-transparent fw-600">🏆 সেরা পণ্য</div>
                <div class="card-body p-0">
                    @foreach($topProducts as $i => $p)
                        <div class="d-flex align-items-center gap-3 px-3 py-2
                             {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div style="width:24px;height:24px;border-radius:50%;
                                        background:{{ ['#0A2540','#1DA1A8','#FF7A18','#22C55E','#FACC15'][$i] }};
                                        color:#fff;font-size:.7rem;font-weight:700;
                                        display:flex;align-items:center;justify-content:center">
                                {{ $i+1 }}
                            </div>
                            <div class="flex-grow-1" style="min-width:0">
                                <div style="font-size:.83rem;font-weight:500;
                                            white-space:nowrap;overflow:hidden;
                                            text-overflow:ellipsis">
                                    {{ $p->product_name }}
                                </div>
                                <div style="font-size:.72rem;color:#6B7280">
                                    {{ $p->total_qty }}টি বিক্রি
                                </div>
                            </div>
                            <div style="font-size:.85rem;font-weight:600;
                                        color:#0A2540;white-space:nowrap">
                                ৳{{ number_format($p->total_revenue) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Top Customers --}}
        <div class="col-lg-4">
            <div class="card chart-card">
                <div class="card-header bg-transparent fw-600">👑 সেরা Customer</div>
                <div class="card-body p-0">
                    @foreach($topCustomers as $i => $customer)
                        <div class="d-flex align-items-center gap-3 px-3 py-2
                             {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div style="width:32px;height:32px;border-radius:50%;
                                        background:linear-gradient(135deg,#0A2540,#1DA1A8);
                                        color:#fff;font-size:.75rem;font-weight:700;
                                        display:flex;align-items:center;justify-content:center">
                                {{ strtoupper(substr($customer->name,0,1)) }}
                            </div>
                            <div class="flex-grow-1" style="min-width:0">
                                <div style="font-size:.83rem;font-weight:500">
                                    {{ Str::limit($customer->name,20) }}
                                </div>
                                <div style="font-size:.72rem;color:#6B7280">
                                    {{ $customer->orders_count }}টি order
                                </div>
                            </div>
                            <div style="font-size:.85rem;font-weight:600;
                                        color:#0A2540;white-space:nowrap">
                                ৳{{ number_format($customer->total_spent) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Recent Orders --}}
        <div class="col-lg-4">
            <div class="card chart-card">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <span class="fw-600">সাম্প্রতিক Orders</span>
                    <a href="{{ route('admin.orders.index') }}"
                       style="font-size:.75rem;color:#1DA1A8;text-decoration:none">
                        সব দেখুন →
                    </a>
                </div>
                <div class="card-body p-0">
                    @foreach($recentOrders as $order)
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="d-flex align-items-center gap-2 px-3 py-2 text-decoration-none
                                  {{ !$loop->last ? 'border-bottom' : '' }}"
                           style="color:inherit;transition:background .15s"
                           onmouseover="this.style.background='#f8fafc'"
                           onmouseout="this.style.background=''">
                            <div class="flex-grow-1" style="min-width:0">
                                <div style="font-size:.8rem;font-weight:600;color:#0A2540">
                                    {{ $order->order_number }}
                                </div>
                                <div style="font-size:.72rem;color:#6B7280">
                                    {{ Str::limit($order->shipping_name,16) }} ·
                                    {{ $order->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <div class="text-end">
                                <div style="font-size:.82rem;font-weight:600">
                                    ৳{{ number_format($order->total) }}
                                </div>
                                <span class="badge bg-{{ $order->status_color }}"
                                      style="font-size:.6rem">
                                    {{ $order->status_label }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- Low Stock Alert --}}
    @if($stats['low_stock'] > 0)
        <div class="alert alert-warning d-flex align-items-center gap-2 mt-4">
            ⚠️
            <span>
                <strong>{{ $stats['low_stock'] }}টি পণ্যের stock কম।</strong>
                <a href="{{ route('admin.products.index', ['stock' => 'low']) }}"
                   class="alert-link ms-1">দেখুন →</a>
            </span>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const primary   = '#0A2540';
const secondary = '#1DA1A8';
const accent    = '#FF7A18';
const border    = '#E5E7EB';

// ── Revenue Line Chart ─────────────────────────────────────
const revenueCtx = document.getElementById('revenueChart').getContext('2d');

const gradient = revenueCtx.createLinearGradient(0, 0, 0, 300);
gradient.addColorStop(0, 'rgba(29,161,168,.18)');
gradient.addColorStop(1, 'rgba(29,161,168,0)');

new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: @json($revenueChart['labels']),
        datasets: [
            {
                label: 'Revenue (৳)',
                data:  @json($revenueChart['revenue']),
                borderColor:     secondary,
                backgroundColor: gradient,
                borderWidth:     2.5,
                pointBackgroundColor: secondary,
                pointRadius:     4,
                pointHoverRadius:6,
                fill:            true,
                tension:         .4,
                yAxisID:         'y',
            },
            {
                label: 'Orders',
                data:  @json($revenueChart['orders']),
                borderColor:     accent,
                backgroundColor: 'transparent',
                borderWidth:     2,
                pointBackgroundColor: accent,
                pointRadius:     3,
                pointHoverRadius:5,
                fill:            false,
                tension:         .4,
                yAxisID:         'y1',
            },
        ],
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { position: 'top', labels: { font:{ size:12 }, color:'#6B7280' } },
            tooltip: {
                callbacks: {
                    label: ctx => ctx.datasetIndex === 0
                        ? ' ৳' + ctx.parsed.y.toLocaleString()
                        : ' ' + ctx.parsed.y + ' orders',
                },
            },
        },
        scales: {
            x: { grid: { color: border }, ticks: { color:'#6B7280', font:{ size:11 } } },
            y: {
                position: 'left',
                grid: { color: border },
                ticks: {
                    color: '#6B7280',
                    font: { size:11 },
                    callback: v => '৳' + v.toLocaleString(),
                },
            },
            y1: {
                position: 'right',
                grid:  { drawOnChartArea: false },
                ticks: { color:'#6B7280', font:{ size:11 } },
            },
        },
    },
});

// ── Status Doughnut Chart ──────────────────────────────────
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels:   @json($orderStatusData['labels']),
        datasets: [{
            data:            @json($orderStatusData['data']),
            backgroundColor: @json($orderStatusData['colors']),
            borderWidth:     2,
            borderColor:     '#fff',
            hoverOffset:     6,
        }],
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { font:{ size:11 }, color:'#6B7280', padding:12 },
            },
        },
        cutout: '65%',
    },
});
</script>
@endpush