<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('ip')) {
            $query->where('ip', $request->ip);
        }

        $logs = $query->paginate(30)->withQueryString();

        // Filter options
        $events = array_keys(AuditLog::EVENT_LABELS);
        $models = AuditLog::distinct()->pluck('model')->filter()->values();

        return view('admin.audit-logs.index', compact('logs', 'events', 'models'));
    }
}
