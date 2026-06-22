<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoteFile extends Model
{
    protected $fillable = ['note_id', 'name', 'path', 'mime_type', 'size'];

    public function note()
    {
        return $this->belongsTo(Note::class);
    }

    public function getFileIcon()
{
    if (str_starts_with($this->mime_type, 'image/')) return 'far fa-image';
    if (str_contains($this->mime_type, 'pdf')) return 'far fa-file-pdf';
    if (str_contains($this->mime_type, 'word') || str_contains($this->mime_type, 'document')) return 'far fa-file-word';
    if (str_contains($this->mime_type, 'excel') || str_contains($this->mime_type, 'spreadsheet')) return 'far fa-file-excel';
    if (str_contains($this->mime_type, 'zip') || str_contains($this->mime_type, 'compressed')) return 'far fa-file-archive';
    return 'far fa-file';
}

    public function getFormattedSize()
    {
        $bytes = $this->size;
        if ($bytes === 0) return '0 Bytes';
        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));
        return number_format($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }

    public function isPreviewable()
    {
        $previewableTypes = [
            'image/',          
            'text/',
            'application/json',
            'application/javascript'
        ];

        foreach ($previewableTypes as $type) {
            if (str_starts_with($this->mime_type, $type)) {
                return true;
            }
        }

        return false;
    }
}
