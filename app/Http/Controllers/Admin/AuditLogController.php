<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->filled('aksi')) {
            $query->where('aksi', $request->aksi);
        }

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('tabel_terkait', 'like', "%{$cari}%")
                  ->orWhere('ip_address', 'like', "%{$cari}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$cari}%"));
            });
        }

        $logs = $query->latest('id')->paginate(20)->withQueryString();
        return view('admin.audit-logs.index', compact('logs'));
    }
}
