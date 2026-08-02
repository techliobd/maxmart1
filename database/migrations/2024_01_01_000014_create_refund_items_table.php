<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refund_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('refund_id')->constrained()->cascadeOnDelete();
            $table->string('refundable_type');
            $table->unsignedBigInteger('refundable_id');
            $table->decimal('refund_amount', 12, 2)->default(0);
            $table->text('reason')->nullable();
            $table->timestamps();
            
            $table->index(['refundable_type', 'refundable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refund_items');
    }
};
