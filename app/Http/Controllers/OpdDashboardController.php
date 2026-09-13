<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class OpdDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. Total seluruh tugas
        $totalTugas = Task::count();

        // 2. Tugas yang sudah dikumpulkan (file_path ada isinya)
        $totalDikumpulkan = Submission::where('user_id', $userId)
            ->whereNotNull('file_path')
            ->count();

        // 3. Tugas yang belum dikirim
        $totalBelumDikumpulkan = max(0, $totalTugas - $totalDikumpulkan);

        return view('opd.dashboard', compact(
            'totalTugas', 
            'totalDikumpulkan', 
            'totalBelumDikumpulkan'
        ));
    }
}