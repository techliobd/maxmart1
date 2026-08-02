<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->latest()->paginate(50);

        $users = \App\Models\User::where('is_staff', true)->orWhere('is_admin', true)->get();

        return view('admin.activity-logs.index', compact('logs', 'users'));
    }

    public function show(ActivityLog $log)
    {
        return view('admin.activity-logs.show', compact('log'));
    }

    public function clear(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365',
        ]);

        $cutoffDate = now()->subDays($request->days);

        $deletedCount = ActivityLog::where('created_at', '<', $cutoffDate)->delete();

        return back()->with('success', "Deleted {$deletedCount} activity logs older than {$request->days} days.");
    }

    public function export(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->latest()->get();

        $csvData = "Date,User,Action,Description,IP Address\n";

        foreach ($logs as $log) {
            $csvData .= sprintf(
                "%s,%s,%s,%s,%s\n",
                $log->created_at->format('Y-m-d H:i:s'),
                $log->user?->name ?? 'System',
                $log->action,
                str_replace('"', '""', $log->description ?? ''),
                $log->ip_address ?? ''
            );
        }

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="activity_logs_' . date('Y-m-d') . '.csv"');
    }
}
