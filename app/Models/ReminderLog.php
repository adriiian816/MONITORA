<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReminderLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'task_id',
        'user_id',
        'recipient_phone',
        'reminder_type',
        'sent_at',
        'status',
        'response_message',
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}