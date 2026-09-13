<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Menampilkan daftar semua tugas.
     */
    public function index()
    {
        $tasks = Task::latest()->get();
        return view('admin.tasks.index', compact('tasks'));
    }

    /**
     * Menampilkan form tambah tugas baru.
     */
    public function create()
    {
        return view('admin.tasks.create');
    }

    /**
     * Menyimpan tugas baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'deadline'    => ['required', 'date'],
        ]);

        Task::create([
            'title'       => $request->title,
            'description' => $request->description,
            'deadline'    => $request->deadline,
        ]);

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Tugas berhasil dibuat.');
    }

    /**
     * Menampilkan detail tugas dan status submission OPD.
     */
    public function show(Task $task)
    {
        $task->load('submissions.user');
        return view('admin.tasks.show', compact('task'));
    }

    /**
     * Menampilkan form edit tugas.
     */
    public function edit(Task $task)
    {
        return view('admin.tasks.edit', compact('task'));
    }

    /**
     * Memperbarui data tugas di database.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'deadline'    => ['required', 'date'],
        ]);

        $task->update([
            'title'       => $request->title,
            'description' => $request->description,
            'deadline'    => $request->deadline,
        ]);

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    /**
     * Menghapus tugas dari database.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Tugas berhasil dihapus.');
    }
}