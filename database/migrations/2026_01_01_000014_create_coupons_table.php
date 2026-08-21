<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['fixed', 'percentage', 'free_shipping']);
            $table->decimal('value', 10, 2);
            $table->decimal('min_order', 10, 2)->default(0.00);
            $table->decimal('max_discount', 10, 2)->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('times_used')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->enum('target_type', ['all', 'b2b', 'b2c'])->default('all');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('discount_wheel_spins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('ip_address');
            $table->string('reward_label');
            $table->enum('reward_type', ['percentage', 'fixed', 'free_shipping', 'none']);
            $table->decimal('reward_value', 10, 2)->default(0.00);
            $table->string('coupon_code')->nullable();
            $table->boolean('is_used')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_wheel_spins');
        Schema::dropIfExists('coupons');
    }
};
