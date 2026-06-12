{{-- resources/views/admin/reviews/index.blade.php --}}
@extends('backend.layouts.masterLayout')
@section('title', 'Reviews')

@section('content')
<div class="container-fluid py-4 px-4">

    <h4 class="mb-4">Review ব্যবস্থাপনা</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>User</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Status</th>
                        <th>তারিখ</th>
                        <th style="width:120px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td>
                                <a href="{{ route('shop.product', $review->product->slug) }}"
                                   target="_blank"
                                   class="text-decoration-none"
                                   style="font-size:.85rem;color:var(--secondary)">
                                    {{ Str::limit($review->product->name, 30) }}
                                </a>
                            </td>
                            <td style="font-size:.85rem">
                                {{ $review->user->name }}
                            </td>
                            <td>
                                <span style="color:var(--warning)">
                                    @for($s=1;$s<=5;$s++)
                                        {{ $s <= $review->rating ? '★' : '☆' }}
                                    @endfor
                                </span>
                            </td>
                            <td style="max-width:280px">
                                @if($review->title)
                                    <div class="fw-600"
                                         style="font-size:.82rem;color:var(--primary)">
                                        {{ $review->title }}
                                    </div>
                                @endif
                                <div class="text-muted"
                                     style="font-size:.8rem">
                                    {{ Str::limit($review->body, 80) }}
                                </div>
                            </td>
                            <td>
                                @if($review->is_approved)
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                            <td style="font-size:.8rem;color:var(--text-secondary)">
                                {{ $review->created_at->format('d M Y') }}
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    {{-- Approve toggle --}}
                                    <form method="POST"
                                          action="{{ route('admin.reviews.approve', $review) }}">
                                        @csrf
                                        <button class="btn btn-sm
                                            {{ $review->is_approved ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                            style="font-size:.72rem">
                                            {{ $review->is_approved ? 'Unapprove' : 'Approve' }}
                                        </button>
                                    </form>
                                    {{-- Delete --}}
                                    <form method="POST"
                                          action="{{ route('admin.reviews.destroy', $review) }}"
                                          onsubmit="return confirm('মুছবেন?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"
                                                style="font-size:.72rem">
                                            মুছুন
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7"
                                class="text-center py-4 text-muted">
                                কোনো review নেই।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reviews->hasPages())
            <div class="card-footer">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
