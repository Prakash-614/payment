<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained('websites')->cascadeOnDelete();
            $table->string('session_token', 64)->unique(); // Token for the hosted checkout URL
            $table->string('transaction_uuid', 64)->unique();
            $table->string('order_id')->nullable(); // e.g. The Soaltee Kathmandu's BOOK-101
            $table->decimal('amount', 12, 2);
            $table->string('currency', 10)->default('USD');
            $table->string('gateway')->nullable(); // paypal | stripe | esewa | khalti
            $table->string('status')->default('pending'); // pending | completed | failed | cancelled
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('return_url'); // where to redirect customer on success
            $table->string('cancel_url'); // where to redirect customer on cancel/failure
            $table->json('gateway_response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
