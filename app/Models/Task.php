<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'submission_format',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function reminderLogs()
    {
        return $this->hasMany(ReminderLog::class);
    }
}