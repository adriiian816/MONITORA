<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Submission;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Total Tugas Aktif
        $totalTugas = Task::count();

        // 2. Total Akun OPD Terdaftar
        $totalOpd = User::where('role', 'opd')->count();

        // 3. Total Laporan Selesai (Sudah Dikumpulkan / file_path ada)
        $totalDikumpulkan = Submission::whereNotNull('file_path')->count();

        // 4. Total Target Keseluruhan (Task * Jumlah OPD)
        $totalTarget = $totalTugas * $totalOpd;

        // 5. Belum Dikumpulkan (Sisa target dikurangi yang sudah terkumpul)
        $totalBelumDikumpulkan = max(0, $totalTarget - $totalDikumpulkan);

        // 6. Riwayat Notifikasi WhatsApp (Opsional)
        $logs = class_exists('\App\Models\WhatsappLog') 
            ? \App\Models\WhatsappLog::with(['user', 'task'])->latest()->take(10)->get() 
            : collect();

        // Kirim semua variabel ke view 'admin.dashboard'
        return view('admin.dashboard', compact(
            'totalTugas',
            'totalOpd',
            'totalDikumpulkan',
            'totalBelumDikumpulkan',
            'logs'
        ));
    }
}