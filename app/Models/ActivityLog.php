<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Loggable;

class ActivityLog extends Model
{
    use HasFactory, Loggable;

    protected $guarded = [];

    protected $casts = [
        'properties' => 'array',
    ];

    public function causer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'causer_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Model::class, 'subject_id', 'id');
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('causer_id', $userId);
    }

    public function scopeForModel($query, string $modelType, ?int $modelId = null)
    {
        $query = $query->where('subject_type', $modelType);
        
        if ($modelId) {
            $query->where('subject_id', $modelId);
        }

        return $query;
    }

    public function scopeAction($query, string $action)
    {
        return $query->where('description', $action);
    }

    public function getFormattedPropertiesAttribute(): array
    {
        $properties = $this->properties ?? [];
        
        $formatted = [];
        foreach ($properties as $key => $value) {
            $formatted[ucfirst(str_replace('_', ' ', $key))] = $value;
        }
        
        return $formatted;
    }

    public function getIconAttribute(): string
    {
        return match($this->description) {
            'created' => 'plus-circle text-green-500',
            'updated' => 'edit text-blue-500',
            'deleted' => 'trash text-red-500',
            'restored' => 'refresh-cw text-yellow-500',
            default => 'info text-gray-500',
        };
    }
}
