<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('event_type'); // product_view, add_to_cart, search, wheel_spin, ai_search
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->json('payload')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('import_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('product_name');
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity');
            $table->decimal('product_weight', 8, 2);
            $table->string('shipping_method')->default('air');
            $table->decimal('freight_cost', 10, 2);
            $table->decimal('customs_duty', 10, 2);
            $table->decimal('vat', 10, 2);
            $table->decimal('total_landed_cost', 12, 2);
            $table->decimal('cost_per_unit', 10, 2);
            $table->decimal('target_price', 10, 2)->nullable();
            $table->decimal('expected_profit', 12, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('flash_sales', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->decimal('discount_percentage', 5, 2)->default(20.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('flash_sales');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('import_calculations');
        Schema::dropIfExists('analytics_events');
    }
};
