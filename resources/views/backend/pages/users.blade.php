
@extends('backend.layouts.masterLayout')
@section('title', 'Customers')

@section('content')
<div class="container-fluid py-4 px-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Customer ব্যবস্থাপনা</h4>
        <a href="{{ route('admin.users.admins') }}" class="btn btn-outline-primary btn-sm">
            👤 Admin তালিকা
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Filter --}}
    <div class="card mb-4">
        <div class="card-body py-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <input type="text" name="search"
                           value="{{ request('search') }}"
                           placeholder="নাম, email বা ফোন দিয়ে খুঁজুন"
                           class="form-control">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">সব Status</option>
                        <option value="active" {{ request('status')==='active'?'selected':'' }}>সক্রিয়</option>
                        <option value="banned" {{ request('status')==='banned'?'selected':'' }}>ব্যানড</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-outline-primary">খুঁজুন</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">রিসেট</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>নাম</th>
                        <th>Email / ফোন</th>
                        <th>Order</th>
                        <th>মোট খরচ</th>
                        <th>যোগদান</th>
                        <th>Status</th>
                        <th style="width:150px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:32px;height:32px;border-radius:50%;
                                                background:linear-gradient(135deg,var(--bs-primary),#1DA1A8);
                                                color:#fff;display:flex;align-items:center;
                                                justify-content:center;font-size:.75rem;font-weight:700">
                                        {{ strtoupper(substr($user->name,0,1)) }}
                                    </div>
                                    {{ $user->name }}
                                </div>
                            </td>
                            <td>
                                <small>{{ $user->email }}</small><br>
                                <small class="text-muted">{{ $user->phone ?? '—' }}</small>
                            </td>
                            <td>{{ $user->orders_count }}টি</td>
                            <td>৳{{ number_format($user->total_spent ?? 0) }}</td>
                            <td><small>{{ $user->created_at }}</small></td>
                            <td>
                                @if($user->is_banned)
                                    <span class="badge bg-danger">ব্যানড</span>
                                @else
                                    <span class="badge bg-success">সক্রিয়</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="btn btn-sm btn-outline-primary">দেখুন</a>
                                    <form method="POST"
                                          action="{{ route('admin.users.ban', $user) }}"
                                          onsubmit="return confirm('{{ $user->is_banned ? 'ব্যান তুলবেন?' : 'ব্যান করবেন?' }}')">
                                        @csrf
                                        <button class="btn btn-sm
                                            {{ $user->is_banned ? 'btn-outline-success' : 'btn-outline-danger' }}">
                                            {{ $user->is_banned ? 'Unban' : 'Ban' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">কোনো customer নেই।</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="card-footer">{{ $users->links() }}</div>
        @endif
    </div>
</div>
@endsection
