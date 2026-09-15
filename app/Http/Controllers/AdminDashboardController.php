<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Submission;
use App\Models\User;
use App\Models\WhatsappLog;
use App\Services\WhatsappService;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Menampilkan statistik ringkasan dan daftar berkas masuk di dashboard admin.
     */
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

        // 6. Riwayat Notifikasi WhatsApp
        $logs = class_exists('\App\Models\WhatsappLog') 
            ? \App\Models\WhatsappLog::with(['user', 'task'])->latest()->take(10)->get() 
            : collect();

        // 7. Ambil daftar seluruh pengumpulan berkas OPD beserta relasi user & task
        $submissions = Submission::with(['user', 'task'])
            ->whereNotNull('file_path')
            ->latest()
            ->get();

        // Kirim semua variabel ke view 'admin.dashboard'
        return view('admin.dashboard', compact(
            'totalTugas',
            'totalOpd',
            'totalDikumpulkan',
            'totalBelumDikumpulkan',
            'logs',
            'submissions'
        ));
    }

    /**
     * Mengubah status berkas, menyimpan catatan revisi, dan mengirim WhatsApp ke OPD.
     */
    public function updateStatus(Request $request, int|string $id)
    {
        $request->validate([
            'status'         => 'required|string',
            'catatan_revisi' => 'nullable|string|max:1000',
        ]);

        $submission = Submission::with(['user', 'task'])->findOrFail($id);
        $submission->status = $request->status;

        // Simpan catatan ke kolom 'catatan_revisi' (sesuaikan jika kolom database Anda bernama 'notes')
        if ($request->status === 'revisi') {
            $submission->catatan_revisi = $request->catatan_revisi;
        } else {
            $submission->catatan_revisi = null;
        }

        $submission->save();

        // --- FITUR KIRIM NOTIFIKASI WHATSAPP KE OPD ---
        $opdName = $submission->user->name ?? 'OPD';
        $taskTitle = $submission->task->title ?? 'Tugas BAPPEDA';
        $phone = $submission->user->phone ?? null; 

        if ($phone) {
            if ($request->status === 'disetujui') {
                $message = "✅ *MONITORA BAPPEDA - BERKAS DISETUJUI*\n\n" .
                           "Halo *{$opdName}*,\n\n" .
                           "Berkas untuk tugas (*{$taskTitle}*) yang Anda kirimkan telah *DISETUJUI* oleh Admin BAPPEDA. Terima kasih!";
                $type = 'Disetujui';
            } else {
                $message = "⚠️ *MONITORA BAPPEDA - PERMINTAAN REVISI*\n\n" .
                           "Halo *{$opdName}*,\n\n" .
                           "Berkas untuk tugas (*{$taskTitle}*) memerlukan *REVISI*.\n\n" .
                           "*Catatan dari Admin:* " . ($request->catatan_revisi ?? '-') . "\n\n" .
                           "Mohon segera diperbaiki dan unggah kembali di sistem MONITORA.";
                $type = 'Revisi';
            }

            // Kirim pesan melalui WhatsAppService (Sertakan user_id & task_id agar tercatat otomatis jika service Anda mendukungnya)
            $sent = WhatsappService::send($phone, $message, $submission->user_id);

            // Simpan riwayat log notifikasi WhatsApp ke database lokal
            if (class_exists(WhatsappLog::class)) {
                WhatsappLog::create([
                    'user_id' => $submission->user_id,
                    'task_id' => $submission->task_id ?? null,
                    'type'    => $type,
                    'status'  => $sent ? 'success' : 'failed',
                ]);
            }
        }
        // ----------------------------------------------

        return redirect()->back()->with('success', 'Status berkas berhasil diperbarui dan notifikasi WhatsApp terkirim ke OPD!');
    }
}