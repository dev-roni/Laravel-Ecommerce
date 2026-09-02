@extends('backend/layouts/masterLayout')
@section('title', 'Audit_logs')

@section('content')
<div class="container-fluid py-4 px-4">

    <h4 class="mb-4">Audit Logs</h4>

    {{-- Filter --}}
    <div class="card mb-4">
        <div class="card-body py-3">
            <form method="GET" class="row g-2 align-items-end">

                <div class="col-md-2">
                    <select name="event" class="form-select form-select-sm">
                        <option value="">সব Event</option>
                        @foreach($events as $event)
                            <option value="{{ $event }}"
                                {{ request('event') === $event ? 'selected' : '' }}>
                                {{ \App\Models\AuditLog::EVENT_LABELS[$event] ?? $event }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="model" class="form-select form-select-sm">
                        <option value="">সব Model</option>
                        @foreach($models as $model)
                            <option value="{{ $model }}"
                                {{ request('model') === $model ? 'selected' : '' }}>
                                {{ $model }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="date" name="date"
                           value="{{ request('date') }}"
                           class="form-control form-control-sm">
                </div>

                <div class="col-md-2">
                    <input type="text" name="ip"
                           value="{{ request('ip') }}"
                           placeholder="IP Address"
                           class="form-control form-control-sm">
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-sm btn-outline-primary">
                        Filter
                    </button>
                    <a href="{{ route('admin.audit-logs.index') }}"
                       class="btn btn-sm btn-outline-secondary">
                        Reset
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- Logs Table --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0"
                   style="font-size:.83rem">
                <thead class="table-light">
                    <tr>
                        <th>সময়</th>
                        <th>User</th>
                        <th>Event</th>
                        <th>Model</th>
                        <th>IP</th>
                        <th>পরিবর্তন</th>
                        <th style="width:70px"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            {{-- সময় --}}
                            <td style="white-space:nowrap">
                                <div>{{ $log->created_at->format('d M Y') }}</div>
                                <div style="font-size:.75rem;color:var(--text-secondary)">
                                    {{ $log->created_at->format('h:i A') }}
                                </div>
                            </td>

                            {{-- User --}}
                            <td>
                                @if($log->user_id)
                                    <div style="font-weight:500">
                                        {{ $log->user_name }}
                                    </div>
                                    <div style="font-size:.72rem">
                                        @if($log->user_role === 'admin')
                                            <span style="color:var(--secondary)">Admin</span>
                                        @else
                                            <span style="color:var(--text-secondary)">Customer</span>
                                        @endif
                                    </div>
                                @else
                                    <span style="color:var(--text-secondary)">System</span>
                                @endif
                            </td>

                            {{-- Event --}}
                            <td>
                                @php
                                    $eventColors = [
                                        'order'   => 'info',
                                        'product' => 'primary',
                                        'user'    => 'warning',
                                        'auth'    => 'danger',
                                        'coupon'  => 'success',
                                        'refund'  => 'danger',
                                        'admin'   => 'secondary',
                                    ];
                                    $prefix = explode('.', $log->event)[0];
                                    $color  = $eventColors[$prefix] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}"
                                      style="font-size:.65rem">
                                    {{ $log->event_label }}
                                </span>
                            </td>

                            {{-- Model --}}
                            <td>
                                @if($log->model)
                                    <span style="color:var(--text-secondary)">
                                        {{ $log->model }}
                                    </span>
                                    @if($log->model_id)
                                        <span style="font-size:.72rem;color:var(--text-secondary)">
                                            #{{ $log->model_id }}
                                        </span>
                                    @endif
                                @else
                                    <span style="color:var(--text-secondary)">—</span>
                                @endif
                            </td>

                            {{-- IP --}}
                            <td style="font-size:.78rem;color:var(--text-secondary);
                                       white-space:nowrap">
                                {{ $log->ip }}
                            </td>

                            {{-- পরিবর্তন সারসংক্ষেপ --}}
                            <td style="max-width:200px">
                                @if($log->old_values || $log->new_values)
                                    @foreach(array_keys($log->new_values ?? []) as $field)
                                        <div style="font-size:.72rem;line-height:1.5">
                                            <span style="color:var(--text-secondary)">
                                                {{ $field }}:
                                            </span>
                                            @if(isset($log->old_values[$field]))
                                                <span style="color:var(--error);
                                                             text-decoration:line-through">
                                                    {{ Str::limit((string)$log->old_values[$field], 15) }}
                                                </span>
                                                →
                                            @endif
                                            <span style="color:var(--success)">
                                                {{ Str::limit((string)$log->new_values[$field], 15) }}
                                            </span>
                                        </div>
                                    @endforeach
                                @else
                                    <span style="color:var(--text-secondary)">—</span>
                                @endif
                            </td>

                            {{-- Detail --}}
                            <td>
                                <a href="{{ route('admin.audit-logs.show', $log) }}"
                                   class="btn btn-sm btn-outline-secondary"
                                   style="font-size:.72rem;padding:.25rem .6rem">
                                    দেখুন
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                কোনো log নেই।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="card-footer">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection