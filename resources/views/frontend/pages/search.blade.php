{{-- resources/views/shop/search.blade.php --}}
@extends('frontend.layouts.masterLayout')
@section('title', 'Search: ' . request('q'))

@section('content')
<div class="container py-4">
    <h4 class="mb-4">
        @if(request('q'))
            "{{ request('q') }}" — এর ফলাফল
            <small class="text-muted fs-6">({{ $products->total() }}টি)</small>
        @else
            সব পণ্য
        @endif
    </h4>

    @if($products->isEmpty())
        <div class="text-center py-5">
            <div style="font-size:60px">🔍</div>
            <p class="text-muted mt-3">কোনো পণ্য পাওয়া যায়নি।</p>
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
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
