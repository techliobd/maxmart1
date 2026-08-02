<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'variables' => 'array',
    ];

    public const TYPE_ORDER_CONFIRMATION = 'order_confirmation';
    public const TYPE_ORDER_STATUS_UPDATE = 'order_status_update';
    public const TYPE_SHIPPING_CONFIRMATION = 'shipping_confirmation';
    public const TYPE_DELIVERY_CONFIRMATION = 'delivery_confirmation';
    public const TYPE_ORDER_CANCELLED = 'order_cancelled';
    public const TYPE_PASSWORD_RESET = 'password_reset';
    public const TYPE_WELCOME_EMAIL = 'welcome_email';
    public const TYPE_NEWSLETTER = 'newsletter';
    public const TYPE_REVIEW_REQUEST = 'review_request';
    public const TYPE_ABANDONED_CART = 'abandoned_cart';

    public static function getTypes(): array
    {
        return [
            self::TYPE_ORDER_CONFIRMATION => 'Order Confirmation',
            self::TYPE_ORDER_STATUS_UPDATE => 'Order Status Update',
            self::TYPE_SHIPPING_CONFIRMATION => 'Shipping Confirmation',
            self::TYPE_DELIVERY_CONFIRMATION => 'Delivery Confirmation',
            self::TYPE_ORDER_CANCELLED => 'Order Cancelled',
            self::TYPE_PASSWORD_RESET => 'Password Reset',
            self::TYPE_WELCOME_EMAIL => 'Welcome Email',
            self::TYPE_NEWSLETTER => 'Newsletter',
            self::TYPE_REVIEW_REQUEST => 'Review Request',
            self::TYPE_ABANDONED_CART => 'Abandoned Cart',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public static function getByType(string $type): ?self
    {
        return self::active()->type($type)->first();
    }

    public function render(array $data): string
    {
        $content = $this->body;

        foreach ($data as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }

        // Replace any unmatched variables with empty string
        $content = preg_replace('/\{\{\s*[a-zA-Z_]+\s*\}\}/', '', $content);

        return $content;
    }

    public function getVariables(): array
    {
        return $this->variables ?? [];
    }
}
