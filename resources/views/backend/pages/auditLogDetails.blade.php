@extends('backend/layouts/masterLayout')
@section('title', 'Audit_log_details')

@section('content')
<div class="container py-4" style="max-width:720px">

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.audit-logs.index') }}"
           class="btn btn-outline-secondary btn-sm">← ফিরুন</a>
        <h5 class="mb-0">Log Detail</h5>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-sm mb-0">
                <tr>
                    <td class="text-muted" style="width:140px">সময়</td>
                    <td>{{ $auditLog->created_at->format('d M Y, h:i:s A') }}</td>
                </tr>
                <tr>
                    <td class="text-muted">User</td>
                    <td>
                        {{ $auditLog->user_name ?? 'System' }}
                        @if($auditLog->user_role)
                            <span class="badge bg-secondary ms-1">
                                {{ $auditLog->user_role }}
                            </span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="text-muted">Event</td>
                    <td>
                        <code>{{ $auditLog->event }}</code>
                        — {{ $auditLog->event_label }}
                    </td>
                </tr>
                <tr>
                    <td class="text-muted">Model</td>
                    <td>
                        {{ $auditLog->model ?? '—' }}
                        @if($auditLog->model_id)
                            #{{ $auditLog->model_id }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="text-muted">IP</td>
                    <td>{{ $auditLog->ip }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Browser</td>
                    <td style="font-size:.82rem">{{ $auditLog->user_agent }}</td>
                </tr>
                <tr>
                    <td class="text-muted">URL</td>
                    <td style="font-size:.82rem;word-break:break-all">
                        <span class="badge bg-light text-dark border me-1">
                            {{ $auditLog->method }}
                        </span>
                        {{ $auditLog->url }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    @if($auditLog->old_values || $auditLog->new_values)
        <div class="row g-3 mt-1">

            @if($auditLog->old_values)
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header fw-600"
                             style="font-size:.82rem;color:var(--error)">
                            আগের মান
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm mb-0">
                                @foreach($auditLog->old_values as $field => $value)
                                    <tr>
                                        <td class="text-muted"
                                            style="font-size:.78rem;width:40%">
                                            {{ $field }}
                                        </td>
                                        <td style="font-size:.82rem;color:var(--error)">
                                            {{ is_array($value)
                                                ? json_encode($value)
                                                : ($value ?? '—') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            @if($auditLog->new_values)
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header fw-600"
                             style="font-size:.82rem;color:var(--success)">
                            নতুন মান
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm mb-0">
                                @foreach($auditLog->new_values as $field => $value)
                                    <tr>
                                        <td class="text-muted"
                                            style="font-size:.78rem;width:40%">
                                            {{ $field }}
                                        </td>
                                        <td style="font-size:.82rem;color:var(--success)">
                                            {{ is_array($value)
                                                ? json_encode($value)
                                                : ($value ?? '—') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    @endif

</div>
@endsection