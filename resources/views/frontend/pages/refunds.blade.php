
@extends('frontend.layouts.masterLayout')
@section('title', 'আমার Refund Requests')

@section('content')
<div class="container py-5">

    <h4 class="mb-4" style="color:var(--primary)">আমার Refund Requests</h4>

    @if($refunds->isEmpty())
        <div class="text-center py-5">
            <div style="font-size:3.5rem;opacity:.2">↩️</div>
            <p class="text-muted mt-3">কোনো refund request নেই।</p>
        </div>
    @else
        <div class="d-flex flex-column gap-3">
            @foreach($refunds as $refund)
                <div class="card"
                     style="border:none;border-radius:12px;
                            box-shadow:0 2px 12px rgba(10,37,64,.06)">
                    <div class="card-body">
                        <div class="row align-items-center g-3">
                            <div class="col-md-3">
                                <div style="font-size:.7rem;color:var(--text-secondary);text-transform:uppercase">Order</div>
                                <div style="font-weight:600;color:var(--primary)">
                                    {{ $refund->order->order_number }}
                                </div>
                                <div style="font-size:.78rem;color:var(--text-secondary)">
                                    {{ $refund->created_at->format('d M Y') }}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div style="font-size:.7rem;color:var(--text-secondary);text-transform:uppercase">পরিমাণ</div>
                                <div style="font-weight:700;color:var(--primary)">
                                    ৳{{ number_format($refund->amount) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div style="font-size:.7rem;color:var(--text-secondary);text-transform:uppercase">পদ্ধতি</div>
                                <div style="font-size:.9rem">
                                    {{ strtoupper($refund->refund_method) }}:
                                    {{ $refund->refund_account }}
                                </div>
                            </div>
                            <div class="col-md-2 text-md-center">
                                <span class="badge bg-{{ $refund->status_color }} px-3 py-2">
                                    {{ $refund->status_label }}
                                </span>
                            </div>
                            <div class="col-md-2">
                                @if($refund->admin_note)
                                    <div style="font-size:.78rem;color:var(--text-secondary);
                                                background:var(--background);border-radius:6px;
                                                padding:.5rem .75rem">
                                        {{ Str::limit($refund->admin_note, 60) }}
                                    </div>
                                @endif
                                @if($refund->status === 'completed' && $refund->transaction_id)
                                    <div style="font-size:.72rem;color:var(--success);margin-top:.3rem">
                                        TXN: {{ $refund->transaction_id }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $refunds->links() }}</div>
    @endif

</div>
@endsection