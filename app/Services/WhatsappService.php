<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\WhatsAppLog;

class WhatsAppService
{
    /**
     * Kirim pesan WhatsApp melalui Fonnte sekaligus catat log ke database lokal.
     */
    public static function send(string $target, string $message, $userId = null, $taskId = null, string $type = 'Notifikasi'): bool
    {
        $token = env('FONNTE_TOKEN');

        if (empty($token) || empty($target)) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62',
            ]);

            $isSuccess = $response->successful();

            // Simpan rekam jejak ke database lokal (whatsapp_logs)
            WhatsappLog::create([
                'user_id' => $userId,
                'task_id' => $taskId,
                'target'  => $target,
                'message' => $message,
                'type'    => $type,
                'status'  => $isSuccess ? 'success' : 'failed',
            ]);

            return $isSuccess;
        } catch (\Exception $e) {
            Log::error('Gagal mengirim WhatsApp Fonnte: ' . $e->getMessage());
            
            // Opsional: Tetap catat log ke database dengan status failed jika terjadiexception
            try {
                WhatsappLog::create([
                    'user_id' => $userId,
                    'task_id' => $taskId,
                    'target'  => $target,
                    'message' => $message,
                    'type'    => $type,
                    'status'  => 'failed',
                ]);
            } catch (\Exception $ex) {
                // Abaikan jika database log gagal
            }

            return false;
        }
    }
}