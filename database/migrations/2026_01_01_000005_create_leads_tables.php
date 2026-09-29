<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Clinic inquiries captured by the Find Your Tier form (plan.md #22, #25).
 * Deliberately avoids any medical/patient-history fields (plan.md #31).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('clinic_name');
            $table->string('email');
            $table->string('phone', 40);
            $table->string('city')->nullable();
            $table->string('website')->nullable();
            $table->string('preferred_contact')->nullable();

            $table->text('challenge')->nullable();
            $table->text('booking_process')->nullable();
            $table->text('inquiry_process')->nullable();
            $table->text('software_usage')->nullable();
            $table->string('tier_interest')->default('not_sure');
            $table->string('tier_label')->nullable();
            $table->text('message')->nullable();

            $table->boolean('consent')->default(false);
            $table->timestamp('consented_at')->nullable();

            $table->string('source_url')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('utm_term')->nullable();
            $table->string('utm_content')->nullable();

            $table->string('status')->default('new');   // new|contacted|qualified|proposal|won|lost
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('hubspot_contact_id')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('tier_interest');
            $table->index('created_at');
        });

        Schema::create('lead_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('body');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_notes');
        Schema::dropIfExists('leads');
    }
};
