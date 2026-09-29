<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Service tiers (plan.md #25). Package inclusions are still being finalized,
 * so features carry their own draft/confirmed/archived status.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name');                        // Recursive 3
            $table->string('slug')->unique();              // recursive-3
            $table->string('model')->nullable();           // Basic model
            $table->string('includes')->nullable();        // Website
            $table->string('summary', 500)->nullable();   // Card copy
            $table->text('short_description')->nullable(); // Meta description
            $table->text('description')->nullable();       // Long copy
            $table->string('audience')->nullable();        // "Who it is for"
            $table->text('problems_solved')->nullable();   // "What problem it solves"
            $table->text('implementation')->nullable();    // "How implementation works"
            $table->string('example_project')->nullable(); // "Example project/capability"
            $table->string('cta_label')->default('Discuss this tier');
            $table->string('cta_url')->nullable();
            $table->string('badge')->nullable();           // Eyebrow chip
            $table->json('highlights')->nullable();        // Short bullet chips
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('pricing_published')->default(false);
            $table->string('price_label')->nullable();
            $table->timestamps();
        });

        Schema::create('service_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_tier_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('draft'); // draft | confirmed | archived
            $table->string('group')->nullable();        // e.g. "Website", "Clinic software"
            $table->unsignedInteger('display_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_features');
        Schema::dropIfExists('service_tiers');
    }
};
