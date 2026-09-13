<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    protected string $apiUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->apiUrl = env('WA_GATEWAY_URL', 'https://api.whatsapp-gateway.com/send');
        $this->apiKey = env('WA_GATEWAY_KEY', '');
    }

    /**
     * Kirim pesan teks pengingat ke nomor WhatsApp OPD.
     */
    public function sendMessage(string $targetPhone, string $message): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->post($this->apiUrl, [
                'target' => $targetPhone,
                'message' => $message,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('WhatsApp Service Error: ' . $e->getMessage());
            return false;
        }
    }
}