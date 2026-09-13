<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Submission;
use App\Models\SubmissionHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OpdSubmissionController extends Controller
{
    /**
     * Menampilkan daftar pengumpulan tugas untuk OPD
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        // Ambil data pengumpulan milik OPD yang sedang login beserta relasi tugasnya
        $submissions = Submission::with(['task', 'histories'])
            ->where('user_id', $user->id)
            ->get();

        return view('opd.submissions.index', compact('submissions'));
    }

    /**
     * Mengunggah / Memperbarui Berkas Laporan OPD (Serta mencatat Riwayat Revisi)
     */
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,doc,docx,xlsx|max:10240', // Maksimal 10MB
        ]);

        /** @var User $user */
        $user = Auth::user();

        $isLate = Carbon::now()->greaterThan($task->deadline);

        $path = $request->file('file')->store('submissions', 'public');
        $fileName = $request->file('file')->getClientOriginalName();

        $existingSubmission = Submission::where('task_id', $task->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingSubmission) {
            // Catat berkas lama ke riwayat sebelum revisi
            SubmissionHistory::create([
                'submission_id' => $existingSubmission->id,
                'file_path'     => $existingSubmission->file_path,
                'file_name'     => $existingSubmission->file_name ?? 'Berkas Sebelumnya',
                'notes'         => $existingSubmission->notes,
            ]);

            // Perbarui berkas dan reset status ke pending untuk verifikasi Admin
            $existingSubmission->update([
                'file_path' => $path,
                'file_name' => $fileName,
                'status'    => 'pending',
                'notes'     => null,
                'is_late'   => $isLate,
            ]);
        } else {
            Submission::create([
                'task_id'   => $task->id,
                'user_id'   => $user->id,
                'file_path' => $path,
                'file_name' => $fileName,
                'status'    => 'pending',
                'is_late'   => $isLate,
            ]);
        }

        return redirect()->back()->with('success', 'Berkas laporan berhasil dikirim.');
    }
}