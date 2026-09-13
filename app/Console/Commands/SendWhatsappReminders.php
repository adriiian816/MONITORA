<?php

namespace App\Console\Commands;

use App\Models\Submission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsAppTaskReminder extends Command
{
    protected $signature = 'wa:send-reminders';
    protected $description = 'Kirim pengingat WA otomatis ke OPD yang belum submit tugas H-1';

    public function handle(): void
    {
        $pendingSubmissions = Submission::with(['task', 'user'])
            ->where('status', 'pending')
            ->whereHas('task', function ($query) {
                $query->whereBetween('deadline', [now(), now()->addDay()]);
            })->get();

        foreach ($pendingSubmissions as $submission) {
            if (!$submission->user->phone) continue;

            $message = "⚠️ *PENGINGAT DEADLINE TUGAS*\n\n";
            $message .= "Halo *{$submission->user->name}*,\n";
            $message .= "Tugas *{$submission->task->title}* akan berakhir besok pada:\n";
            $message .= "⏰ *" . date('d-m-Y H:i', strtotime($submission->task->deadline)) . "*\n\n";
            $message .= "Harap segera mengunggah berkas Anda ke sistem.";

            try {
                Http::withHeaders([
                    'Authorization' => config('services.fonnte.token'),
                ])->post('https://api.fonnte.com/send', [
                    'target'  => $submission->user->phone,
                    'message' => $message,
                ]);
            } catch (\Exception $e) {
                Log::error("Gagal kirim pengingat WA: " . $e->getMessage());
            }
        }
    }
}