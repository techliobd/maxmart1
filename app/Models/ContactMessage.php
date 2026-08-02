<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_read' => 'boolean',
        'is_replied' => 'boolean',
        'read_at' => 'datetime',
        'replied_at' => 'datetime',
    ];

    public const STATUS_NEW = 'new';
    public const STATUS_READ = 'read';
    public const STATUS_REPLIED = 'replied';
    public const STATUS_ARCHIVED = 'archived';

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeReplied($query)
    {
        return $query->where('is_replied', true);
    }

    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
            'status' => self::STATUS_READ,
        ]);
    }

    public function markAsReplied(): void
    {
        $this->update([
            'is_replied' => true,
            'replied_at' => now(),
            'status' => self::STATUS_REPLIED,
        ]);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_NEW => 'New',
            self::STATUS_READ => 'Read',
            self::STATUS_REPLIED => 'Replied',
            self::STATUS_ARCHIVED => 'Archived',
            default => ucfirst($this->status),
        };
    }
}
