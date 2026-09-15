<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id', 
        'user_id', 
        'file_path', 
        'file_name', 
        'status', 
        'notes', 
        'catatan_revisi', 
        'is_late'
    ];

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

    /**
     * Accessor agar $submission->catatan_revisi membaca dari kolom 'notes' jika kolom catatan_revisi belum ada di DB.
     */
    public function getCatatanRevisiAttribute()
    {
        return $this->attributes['catatan_revisi'] ?? $this->attributes['notes'] ?? null;
    }

    /**
     * Mutator agar saat menyimpan $submission->catatan_revisi otomatis mengisi kolom 'notes'.
     */
    public function setCatatanRevisiAttribute(?string $value): void
    {
        $this->attributes['notes'] = $value;
        if (array_key_exists('catatan_revisi', $this->attributes)) {
            $this->attributes['catatan_revisi'] = $value;
        }
    }
}