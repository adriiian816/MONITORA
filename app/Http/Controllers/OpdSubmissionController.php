<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;

class OpdSubmissionController extends Controller
{
    /**
     * Menampilkan daftar berkas submission HANYA milik OPD yang sedang login.
     */
    public function index()
    {
        // PERBAIKAN UTAMA: Tambahkan filter berdasarkan user_id yang sedang login
        $submissions = Submission::where('user_id', Auth::id())->latest()->get();
        
        return view('opd.submissions.index', compact('submissions'));
    }

    /**
     * Menampilkan form untuk membuat submission baru.
     */
    public function create()
    {
        return view('opd.submissions.create');
    }

    /**
     * Menyimpan submission baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file'  => 'required|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:20480',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions', 'public');
        }

        Submission::create([
            'user_id'   => Auth::id(),
            'title'     => $request->title,
            'file_path' => $filePath,
            'status'    => 'dikirim',
        ]);

        return redirect()->route('opd.submissions.index')->with('success', 'Berkas berhasil dikirim!');
    }

    /**
     * Menampilkan rincian submission (Pastikan milik OPD yang login).
     */
    public function show(int|string $id)
    {
        // PERBAIKAN: Pastikan OPD hanya bisa melihat miliknya sendiri (mencegah akses via URL ID acak)
        $submission = Submission::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        return view('opd.submissions.show', compact('submission'));
    }

    /**
     * Menampilkan halaman edit berkas.
     */
    public function edit(int|string $id)
    {
        $submission = Submission::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        return view('opd.submissions.edit', compact('submission'));
    }

    /**
     * Memproses pembaruan berkas/kirim ulang.
     */
    public function update(Request $request, int|string $id)
    {
        $submission = Submission::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'title'     => 'nullable|string|max:255',
            'file'      => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:20480',
            'berkas'    => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:20480',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:20480',
        ]);

        $uploadedFile = $request->file('file') ?? $request->file('berkas') ?? $request->file('file_path');

        if ($uploadedFile) {
            if ($submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
                Storage::disk('public')->delete($submission->file_path);
            }

            $submission->file_path = $uploadedFile->store('submissions', 'public');
        }

        if ($request->has('title')) {
            $submission->title = $request->title;
        }

        $submission->status = 'dikirim';
        $submission->save();

        if (Route::has('opd.submissions.index')) {
            return redirect()->route('opd.submissions.index')->with('success', 'Berkas berhasil dikirim!');
        }

        return redirect()->back()->with('success', 'Berkas berhasil dikirim!');
    }

    /**
     * Menghapus submission.
     */
    public function destroy(int|string $id)
    {
        $submission = Submission::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
            Storage::disk('public')->delete($submission->file_path);
        }

        $submission->delete();

        return redirect()->route('opd.submissions.index')->with('success', 'Berkas berhasil dihapus.');
    }
}