<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RagDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'original_filename',
        'stored_path',
        'mime_type',
        'uploaded_by',
        'status',
        'processing_status',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
