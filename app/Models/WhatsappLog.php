<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppLog extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_logs';

    protected $fillable = [
        'user_id',
        'task_id', // Tambahkan ini agar sinkron dengan AdminDashboardController
        'target',
        'message',
        'type',    // Tambahkan ini untuk menyimpan jenis notifikasi (Disetujui/Revisi)
        'status',
    ];

    /**
     * Relasi ke model User (OPD penerima)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke model Task (Tugas terkait) - Opsional
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}