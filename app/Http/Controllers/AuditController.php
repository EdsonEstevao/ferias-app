<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        // Filtros
        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $logs = $query->paginate(20);

        // // Estatísticas
        // $stats = [
        //     'created' => AuditLog::where('action', 'created')->count(),
        //     'updated' => AuditLog::where('action', 'updated')->count(),
        //     'deleted' => AuditLog::where('action', 'deleted')->count(),
        // ];

        // $users = User::whereHas('auditLogs')->get();

         // Estatísticas - CORRIGIDO
        $stats = [
            'created' => AuditLog::where('action', 'created')->count(),
            'updated' => AuditLog::where('action', 'updated')->count(),
            'deleted' => AuditLog::where('action', 'deleted')->count(),
            'login' => AuditLog::where('action', 'login')->count(),
            'logout' => AuditLog::where('action', 'logout')->count(),
        ];

        // CORREÇÃO: Buscar usuários que possuem logs de auditoria
        $users = User::whereHas('auditLogsAsActor')->get();


        return view('admin.audit.index', compact('logs', 'stats', 'users'));
    }

    public function show(AuditLog $audit, Request $request)
    {
        // dd($audit, $request->all());
        // Carrega o relacionamento
        $auditLog = $audit->load('user');
        return view('admin.audit.show', compact('auditLog'));
    }
}
