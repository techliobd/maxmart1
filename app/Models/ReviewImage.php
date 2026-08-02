<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Intervention\Image\Laravel\Facades\Image;

class ReviewImage extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }

    public function getThumbUrlAttribute(): string
    {
        if (!$this->image_path) {
            return '';
        }
        $path = pathinfo($this->image_path, PATHINFO_DIRNAME);
        $filename = pathinfo($this->image_path, PATHINFO_FILENAME);
        $extension = pathinfo($this->image_path, PATHINFO_EXTENSION);
        return asset("storage/{$path}/thumbs/{$filename}_thumb.{$extension}");
    }

    public function getFullUrlAttribute(): string
    {
        return $this->image_path ? asset("storage/{$this->image_path}") : '';
    }
}
