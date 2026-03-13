<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoUpload extends Model
{
    protected $fillable = [
        'upload_id',
        'filename',
        'original_name',
        'total_size',
        'total_chunks',
        'uploaded_chunks',
        'chunk_status',
        'status',
        's3_key',
        'completed_at'
    ];

    protected $casts = [
        'chunk_status' => 'array',
        'completed_at' => 'datetime'
    ];

    public function getProgressPercentage(): float
    {
        return $this->total_chunks > 0 ? ($this->uploaded_chunks / $this->total_chunks) * 100 : 0;
    }

    public function isComplete(): bool
    {
        return $this->uploaded_chunks >= $this->total_chunks;
    }
}