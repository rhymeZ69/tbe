<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quote_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();    // TBE-2025-0001
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete();
            $table->string('destination_port')->nullable();
            $table->enum('product_interest', [
                'beef', 'mutton', 'meat_mixed', 'garments', 'rice', 'vegetables', 'multiple', 'other'
            ])->default('other');
            $table->decimal('quantity_mt', 10, 2)->nullable();
            $table->string('packaging_notes')->nullable();
            $table->text('message')->nullable();
            $table->enum('status', ['new', 'in_progress', 'quoted', 'won', 'lost', 'spam'])
                  ->default('new')->index();
            $table->text('admin_notes')->nullable();
            $table->string('source')->nullable();     // website form, whatsapp, email, referral
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('contacted_at')->nullable();
            $table->timestamp('quoted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_at']);
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quote_enquiries');
    }
};