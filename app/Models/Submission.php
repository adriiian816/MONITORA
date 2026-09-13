<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'user_id', 'file_path', 'file_name', 'status', 'notes', 'is_late'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function histories()
    {
        return $this->hasMany(SubmissionHistory::class)->orderBy('created_at', 'desc');
    }
}