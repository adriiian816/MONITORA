<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    /**
     * Menampilkan daftar semua tugas di panel Admin
     */
    public function index()
    {
        $tasks = Task::with('submissions')->orderBy('deadline', 'asc')->get();
        return view('admin.tasks.index', compact('tasks'));
    }

    /**
     * Form tambah tugas baru
     */
    public function create()
    {
        return view('admin.tasks.create');
    }

    /**
     * Simpan tugas baru ke database dan bagikan secara otomatis ke seluruh akun OPD
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline'    => 'required|date',
        ]);

        // 1. Buat Task baru
        $task = Task::create([
            'title'       => $request->title,
            'description' => $request->description,
            'deadline'    => $request->deadline,
        ]);

        // 2. Buat entri submission kosong untuk setiap user bernilai role 'opd'
        $opdUsers = User::where('role', 'opd')->get();
        foreach ($opdUsers as $opd) {
            Submission::create([
                'task_id' => $task->id,
                'user_id' => $opd->id,
                'status'  => 'pending',
            ]);
        }

        return redirect()->route('admin.tasks.index')->with('success', 'Tugas berhasil dibuat dan dibagikan ke seluruh OPD.');
    }

    /**
     * Detail tugas dan daftar submission OPD
     */
    public function show(Task $task)
    {
        $task->load('submissions.user');
        return view('admin.tasks.show', compact('task'));
    }

    /**
     * Form edit tugas
     */
    public function edit(Task $task)
    {
        return view('admin.tasks.edit', compact('task'));
    }

    /**
     * Perbarui data tugas
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline'    => 'required|date',
        ]);

        $task->update([
            'title'       => $request->title,
            'description' => $request->description,
            'deadline'    => $request->deadline,
        ]);

        return redirect()->route('admin.tasks.index')->with('success', 'Tugas berhasil diperbarui.');
    }

    /**
     * Hapus tugas beserta data pengumpulannya
     */
    public function destroy(Task $task)
    {
        $task->submissions()->delete();
        $task->delete();
        
        return redirect()->route('admin.tasks.index')->with('success', 'Tugas berhasil dihapus.');
    }

    /**
     * Verifikasi Status Laporan oleh Admin BAPPEDA (ACC / Revisi)
     */
    public function verifySubmission(Request $request, Submission $submission)
    {
        $request->validate([
            'status' => 'required|in:approved,revision',
            'notes'  => 'nullable|string',
        ]);

        $submission->update([
            'status' => $request->status,
            'notes'  => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Status verifikasi berhasil diperbarui.');
    }

    /**
     * Stream PDF Preview In-App
     */
    public function previewPdf(Submission $submission)
    {
        if (!Storage::disk('public')->exists($submission->file_path)) {
            abort(404, 'File dokumen tidak ditemukan.');
        }

        $filePath = Storage::disk('public')->path($submission->file_path);

        return response()->file($filePath, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . ($submission->file_name ?? 'dokumen.pdf') . '"'
        ]);
    }
}