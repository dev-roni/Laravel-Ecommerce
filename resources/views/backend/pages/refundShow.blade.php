
@extends('backend.layouts.masterLayout')
@section('title', 'Refund Details')

@section('content')
<div class="container py-4" style="max-width:820px">

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.refunds.index') }}"
           class="btn btn-outline-secondary btn-sm">← ফিরুন</a>
        <h5 class="mb-0">Refund — {{ $refund->order->order_number }}</h5>
        <span class="badge bg-{{ $refund->status_color }}">
            {{ $refund->status_label }}
        </span>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">

        {{-- বাম: Details --}}
        <div class="col-md-7">

            {{-- Customer + Order Info --}}
            <div class="card mb-4">
                <div class="card-header fw-600">তথ্য</div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="text-muted">Customer</td>
                            <td>{{ $refund->user->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email</td>
                            <td>{{ $refund->user->email }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Order</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $refund->order) }}">
                                    {{ $refund->order->order_number }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Order Total</td>
                            <td>৳{{ number_format($refund->order->total) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Refund Amount</td>
                            <td style="font-weight:700;color:var(--primary)">
                                ৳{{ number_format($refund->amount) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Refund পদ্ধতি</td>
                            <td>{{ strtoupper($refund->refund_method) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Account</td>
                            <td>{{ $refund->refund_account }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Request তারিখ</td>
                            <td>{{ $refund->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        @if($refund->resolved_at)
                            <tr>
                                <td class="text-muted">Resolved</td>
                                <td>{{ $refund->resolved_at->format('d M Y') }}</td>
                            </tr>
                        @endif
                        @if($refund->transaction_id)
                            <tr>
                                <td class="text-muted">TXN ID</td>
                                <td style="color:var(--success);font-weight:600">
                                    {{ $refund->transaction_id }}
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Reason --}}
            <div class="card mb-4">
                <div class="card-header fw-600">Customer-এর কারণ</div>
                <div class="card-body">
                    <p class="mb-0" style="font-size:.9rem;line-height:1.7">
                        {{ $refund->reason }}
                    </p>
                </div>
            </div>

            {{-- Order Items --}}
            <div class="card">
                <div class="card-header fw-600">Order Items</div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        @foreach($refund->order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}
                                    @if($item->variant_label)
                                        <small class="text-muted">({{ $item->variant_label }})</small>
                                    @endif
                                </td>
                                <td class="text-end">× {{ $item->quantity }}</td>
                                <td class="text-end fw-600">৳{{ number_format($item->subtotal) }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>

        </div>

        {{-- ডান: Action --}}
        <div class="col-md-5">
            @if(!in_array($refund->status, ['completed', 'rejected']))
                <div class="card">
                    <div class="card-header fw-600">Status আপডেট</div>
                    <div class="card-body">
                        <form method="POST"
                              action="{{ route('admin.refunds.update', $refund) }}">
                            @csrf @method('PATCH')

                            <div class="mb-3">
                                <label class="form-label">Status *</label>
                                <select name="status" class="form-select" id="refundStatus"
                                        onchange="toggleTxnField(this.value)">
                                    @foreach(\App\Models\Refund::STATUS_LABELS as $key => $val)
                                        @if($key !== 'pending')
                                            <option value="{{ $key }}"
                                                {{ $refund->status === $key ? 'selected' : '' }}>
                                                {{ $val['label'] }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            {{-- Transaction ID — completed হলে দেখাবে --}}
                            <div class="mb-3" id="txnField"
                                 style="display:{{ $refund->status === 'approved' ? 'block' : 'none' }}">
                                <label class="form-label">Transaction ID</label>
                                <input type="text" name="transaction_id"
                                       value="{{ old('transaction_id', $refund->transaction_id) }}"
                                       class="form-control @error('transaction_id') is-invalid @enderror"
                                       placeholder="SSLCommerz / bKash TXN ID">
                                @error('transaction_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Admin Note</label>
                                <textarea name="admin_note" rows="3"
                                          class="form-control"
                                          placeholder="Customer-কে দেখানো হবে...">{{ old('admin_note', $refund->admin_note) }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                আপডেট করুন
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-4">
                        <span class="badge bg-{{ $refund->status_color }} px-4 py-2 fs-6">
                            {{ $refund->status_label }}
                        </span>
                        @if($refund->admin_note)
                            <p class="text-muted small mt-3 mb-0">{{ $refund->admin_note }}</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>

<script>
function toggleTxnField(status) {
    document.getElementById('txnField').style.display =
        status === 'completed' ? 'block' : 'none';
}
</script>
@endsection