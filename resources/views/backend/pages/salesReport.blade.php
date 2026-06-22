{{-- resources/views/admin/sales-report.blade.php --}}
@extends('backend.layouts.masterLayout')
@section('title', 'Sales Report')

@push('styles')
<style>
.report-stat { border:none; border-radius:10px; background:#fff; box-shadow:0 2px 12px rgba(10,37,64,.06); }
</style>
@endpush

@section('content')
<div class="container-fluid py-4 px-4">

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">← Dashboard</a>
        <h4 class="mb-0">Sales Report</h4>
    </div>

    {{-- Filter --}}
    <div class="card mb-4" style="border:none;border-radius:12px;box-shadow:0 2px 12px rgba(10,37,64,.06)">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">

                {{-- Quick period --}}
                <div class="col-md-3">
                    <label class="form-label small fw-600">Quick Select</label>
                    <div class="d-flex gap-1 flex-wrap">
                        @foreach(['7'=>'৭ দিন','30'=>'৩০ দিন','90'=>'৯০ দিন','365'=>'১ বছর'] as $val=>$lbl)
                            <a href="{{ route('admin.sales.report', ['period'=>$val]) }}"
                               class="btn btn-sm {{ request('period',$val==30?'30':'') == $val && !request('from') ? 'btn-primary' : 'btn-outline-secondary' }}">
                                {{ $lbl }}
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Custom range --}}
                <div class="col-md-3">
                    <label class="form-label small fw-600">শুরু</label>
                    <input type="date" name="from"
                           value="{{ request('from', $from->format('Y-m-d')) }}"
                           class="form-control form-control-sm">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-600">শেষ</label>
                    <input type="date" name="to"
                           value="{{ request('to', $to->format('Y-m-d')) }}"
                           class="form-control form-control-sm">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary w-100">Filter করুন</button>
                </div>

            </form>
        </div>
    </div>

    {{-- Summary Stats --}}
    <div class="row g-3 mb-4">
        @foreach([
            ['label'=>'মোট Revenue','value'=>'৳'.number_format($summary['revenue']),'color'=>'#22C55E'],
            ['label'=>'মোট Order','value'=>$summary['orders'].'টি','color'=>'#1DA1A8'],
            ['label'=>'Paid Order','value'=>$summary['paid'].'টি','color'=>'#0A2540'],
            ['label'=>'গড় Order Value','value'=>'৳'.number_format($summary['avg_order']),'color'=>'#FF7A18'],
            ['label'=>'বাতিল Order','value'=>$summary['cancelled'].'টি','color'=>'#EF4444'],
        ] as $s)
            <div class="col-xl col-md-4">
                <div class="report-stat p-3 h-100"
                     style="border-left:4px solid {{ $s['color'] }}">
                    <div style="font-size:.72rem;color:#6B7280;text-transform:uppercase;letter-spacing:.06em">
                        {{ $s['label'] }}
                    </div>
                    <div style="font-size:1.6rem;font-weight:700;color:#0A2540;margin-top:.2rem">
                        {{ $s['value'] }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">

        {{-- Daily Revenue Chart --}}
        <div class="col-lg-8">
            <div class="card" style="border:none;border-radius:12px;box-shadow:0 2px 12px rgba(10,37,64,.06)">
                <div class="card-header bg-transparent fw-600">দৈনিক Revenue</div>
                <div class="card-body">
                    <canvas id="dailyChart" height="120"></canvas>
                </div>
            </div>
        </div>

        {{-- Payment Breakdown --}}
        <div class="col-lg-4">
            <div class="card h-100" style="border:none;border-radius:12px;box-shadow:0 2px 12px rgba(10,37,64,.06)">
                <div class="card-header bg-transparent fw-600">Payment পদ্ধতি</div>
                <div class="card-body p-0">
                    @foreach($paymentBreakdown as $p)
                        <div class="d-flex align-items-center justify-content-between
                                    px-3 py-2 border-bottom">
                            <div>
                                <div style="font-size:.85rem;font-weight:500">
                                    {{ ['cod'=>'Cash on Delivery','online'=>'Online','bkash'=>'bKash','nagad'=>'Nagad','card'=>'Card'][$p->payment_method] ?? $p->payment_method }}
                                </div>
                                <div style="font-size:.72rem;color:#6B7280">{{ $p->count }}টি order</div>
                            </div>
                            <div style="font-size:.9rem;font-weight:600;color:#0A2540">
                                ৳{{ number_format($p->total) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Top Products Table --}}
        <div class="col-12">
            <div class="card" style="border:none;border-radius:12px;box-shadow:0 2px 12px rgba(10,37,64,.06)">
                <div class="card-header bg-transparent fw-600">🏆 সেরা বিক্রিত পণ্য</div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>পণ্যের নাম</th>
                                <th>বিক্রি (পরিমাণ)</th>
                                <th>Revenue</th>
                                <th>Revenue Bar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $maxRev = $topProducts->max('revenue') ?: 1; @endphp
                            @foreach($topProducts as $i => $p)
                                <tr>
                                    <td>
                                        <span style="font-weight:700;color:{{ ['#0A2540','#1DA1A8','#FF7A18','#22C55E','#FACC15'][$i] ?? '#6B7280' }}">
                                            {{ $i+1 }}
                                        </span>
                                    </td>
                                    <td style="font-weight:500">{{ $p->product_name }}</td>
                                    <td>{{ $p->qty }}টি</td>
                                    <td style="font-weight:600">৳{{ number_format($p->revenue) }}</td>
                                    <td style="min-width:150px">
                                        <div style="height:6px;background:#E5E7EB;border-radius:3px;overflow:hidden">
                                            <div style="height:100%;width:{{ round(($p->revenue/$maxRev)*100) }}%;background:#1DA1A8;border-radius:3px"></div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const border = '#E5E7EB';

// Daily Revenue Bar Chart
const ctx = document.getElementById('dailyChart').getContext('2d');
const grad = ctx.createLinearGradient(0, 0, 0, 250);
grad.addColorStop(0, 'rgba(29,161,168,.7)');
grad.addColorStop(1, 'rgba(29,161,168,.1)');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($dailyRevenue->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))),
        datasets: [{
            label:           'Revenue (৳)',
            data:            @json($dailyRevenue->pluck('revenue')->map(fn($v) => round($v))),
            backgroundColor: grad,
            borderColor:     '#1DA1A8',
            borderWidth:     1.5,
            borderRadius:    4,
        }],
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => ' ৳' + ctx.parsed.y.toLocaleString() } },
        },
        scales: {
            x: { grid: { color: border }, ticks: { color:'#6B7280', font:{ size:10 } } },
            y: {
                grid: { color: border },
                ticks: { color:'#6B7280', font:{ size:11 }, callback: v => '৳'+v.toLocaleString() },
            },
        },
    },
});
</script>
@endpush