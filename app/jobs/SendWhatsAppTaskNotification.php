<?php

namespace App\Jobs;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsAppTaskNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    public User $user;

    /**
     * @var Task
     */
    public Task $task;

    public function __construct(User $user, Task $task)
    {
        $this->user = $user;
        $this->task = $task;
    }

    public function handle(): void
    {
        if (!$this->user->phone) {
            return;
        }

        $message = "📢 *NOTIFIKASI TUGAS BARU*\n\n";
        $message .= "Halo *{$this->user->name}*,\n";
        $message .= "Ada tugas baru yang perlu ditindaklanjuti:\n\n";
        $message .= "📌 *Judul:* {$this->task->title}\n";
        $message .= "⏰ *Deadline:* " . date('d-m-Y H:i', strtotime($this->task->deadline)) . "\n\n";
        $message .= "📝 *Deskripsi:*\n{$this->task->description}\n\n";
        $message .= "Silakan login ke portal untuk mengunggah dokumen submission Anda.";

        try {
            $response = Http::withHeaders([
                'Authorization' => config('services.fonnte.token'),
            ])->post('https://api.fonnte.com/send', [
                'target'  => $this->user->phone,
                'message' => $message,
            ]);

            if ($response->failed()) {
                Log::error("Gagal mengirim WA ke {$this->user->phone}: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Error Job WhatsApp: " . $e->getMessage());
        }
    }
}