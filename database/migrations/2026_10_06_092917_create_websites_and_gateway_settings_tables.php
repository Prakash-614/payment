<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('websites', function (Blueprint $table) {
            $table->id();
            $table->string('name');                      // e.g. "The Soaltee Kathmandu"
            $table->string('domain')->unique();          // e.g. "hotelinn.com"
            $table->string('public_key', 64)->unique();  // pk_live_xxxx / pk_test_xxxx
            $table->string('secret_key', 64)->unique();  // sk_live_xxxx / sk_test_xxxx
            $table->string('webhook_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('website_gateway_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained('websites')->cascadeOnDelete();
            $table->string('gateway');                  // paypal, stripe, esewa, khalti, etc.
            $table->boolean('is_enabled')->default(false);
            $table->string('mode')->default('sandbox'); // sandbox | live
            $table->text('sandbox_credentials')->nullable(); // encrypted JSON
            $table->text('live_credentials')->nullable();    // encrypted JSON
            $table->timestamps();

            $table->unique(['website_id', 'gateway']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_gateway_settings');
        Schema::dropIfExists('websites');
    }
};
