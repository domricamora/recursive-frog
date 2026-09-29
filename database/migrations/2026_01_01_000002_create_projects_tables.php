<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Portfolio projects and their key capabilities (plan.md #25). */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('category')->nullable();
            $table->string('client')->nullable();
            $table->string('summary', 500);
            $table->text('overview')->nullable();
            $table->text('business_problem')->nullable();
            $table->text('solution')->nullable();
            $table->json('technologies')->nullable();
            $table->json('integrations')->nullable();
            $table->text('results')->nullable();   // Verified results only.
            $table->string('external_url')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('accent', 32)->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('published')->default(true);
            $table->unsignedInteger('display_order')->default(0);
            $table->timestamps();
        });

        Schema::create('project_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_features');
        Schema::dropIfExists('projects');
    }
};
