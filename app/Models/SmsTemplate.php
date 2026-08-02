<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
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
    public const TYPE_OTP_VERIFICATION = 'otp_verification';

    public static function getTypes(): array
    {
        return [
            self::TYPE_ORDER_CONFIRMATION => 'Order Confirmation',
            self::TYPE_ORDER_STATUS_UPDATE => 'Order Status Update',
            self::TYPE_SHIPPING_CONFIRMATION => 'Shipping Confirmation',
            self::TYPE_DELIVERY_CONFIRMATION => 'Delivery Confirmation',
            self::TYPE_OTP_VERIFICATION => 'OTP Verification',
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

        return $content;
    }
}
