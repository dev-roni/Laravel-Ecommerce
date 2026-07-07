
@extends('backend.layouts.masterLayout')
@section('title', 'Refund Management')

@section('content')
<div class="container-fluid py-4 px-4">

    <h4 class="mb-4">Refund ব্যবস্থাপনা</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Summary --}}
    <div class="row g-3 mb-4">
        @foreach([
            ['label'=>'Pending',   'value'=>$summary['pending'],   'color'=>'warning'],
            ['label'=>'Approved',  'value'=>$summary['approved'],  'color'=>'info'],
            ['label'=>'Completed', 'value'=>$summary['completed'], 'color'=>'success'],
            ['label'=>'মোট Refund','value'=>'৳'.number_format($summary['total_amt']), 'color'=>'danger'],
        ] as $s)
            <div class="col-md-3">
                <div class="card border-0 shadow-sm border-start border-4 border-{{ $s['color'] }}">
                    <div class="card-body">
                        <div class="text-muted small">{{ $s['label'] }}</div>
                        <div class="fs-4 fw-bold">{{ $s['value'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Filter --}}
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="d-flex gap-2 align-items-center">
                <select name="status" class="form-select form-select-sm w-auto">
                    <option value="">সব Status</option>
                    @foreach(\App\Models\Refund::STATUS_LABELS as $key => $val)
                        <option value="{{ $key }}"
                            {{ request('status') === $key ? 'selected' : '' }}>
                            {{ $val['label'] }}
                        </option>
                    @endforeach
                </select>
                <button class="btn btn-sm btn-outline-primary">Filter</button>
                <a href="{{ route('admin.refunds.index') }}"
                   class="btn btn-sm btn-outline-secondary">Reset</a>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>পরিমাণ</th>
                        <th>পদ্ধতি</th>
                        <th>কারণ</th>
                        <th>Status</th>
                        <th>তারিখ</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($refunds as $refund)
                        <tr>
                            <td>
                                <a href="{{ route('admin.refunds.show', $refund) }}"
                                   style="color:var(--secondary);text-decoration:none;font-weight:600">
                                    {{ $refund->order->order_number }}
                                </a>
                            </td>
                            <td style="font-size:.85rem">{{ $refund->user->name }}</td>
                            <td style="font-weight:600">৳{{ number_format($refund->amount) }}</td>
                            <td>
                                <span style="font-size:.8rem">
                                    {{ strtoupper($refund->refund_method) }}<br>
                                    <span style="color:var(--text-secondary)">
                                        {{ $refund->refund_account }}
                                    </span>
                                </span>
                            </td>
                            <td style="max-width:180px;font-size:.82rem;color:var(--text-secondary)">
                                {{ Str::limit($refund->reason, 60) }}
                            </td>
                            <td>
                                <span class="badge bg-{{ $refund->status_color }}">
                                    {{ $refund->status_label }}
                                </span>
                            </td>
                            <td style="font-size:.8rem">
                                {{ $refund->created_at->format('d M Y') }}
                            </td>
                            <td>
                                <a href="{{ route('admin.refunds.show', $refund) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    বিস্তারিত
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                কোনো refund request নেই।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($refunds->hasPages())
            <div class="card-footer">{{ $refunds->links() }}</div>
        @endif
    </div>
</div>
@endsection
