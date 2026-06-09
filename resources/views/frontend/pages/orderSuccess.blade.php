@extends('frontend/layouts/masterLayout')

@section('content')
<div class="container py-5">
    <div class="text-center py-5">
        <div style="font-size:70px">✅</div>
        <h3 class="mt-3 text-primary-c">Order সফলভাবে হয়েছে!</h3>
        <p class="text-muted">
            আপনার Order নম্বর:
            <strong class="text-primary-c">{{ $order->order_number }}</strong>
        </p>
        <p class="text-muted">
            শীঘ্রই আপনার সাথে যোগাযোগ করা হবে।
        </p>

        <div class="card mx-auto mt-4" style="max-width:400px">
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr>
                        <td class="text-muted">মোট পরিমাণ</td>
                        <td class="fw-bold text-primary-c">৳{{ number_format($order->total) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Payment পদ্ধতি</td>
                        <td>{{ $order->payment_method_label }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            <span class="badge bg-{{ $order->status_color }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="mt-4 d-flex gap-3 justify-content-center">
            <a href="{{ route('orders.show', $order) }}"
               class="btn btn-outline-secondary">
                Order বিস্তারিত দেখুন
            </a>
            <a href="{{ route('shop.index') }}"
               class="btn btn-primary">
                কেনাকাটা চালিয়ে যান
            </a>
        </div>
    </div>
</div>
@endsection