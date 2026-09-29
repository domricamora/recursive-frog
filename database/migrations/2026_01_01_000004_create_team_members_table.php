<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Team profiles shown on the About page and home page (plan.md #14, #34). */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();     // Sales / Front / Tech are roles-only
            $table->string('role');
            $table->string('focus', 255)->nullable();
            $table->text('bio')->nullable();
            $table->json('highlights')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('is_specialist')->default(false);
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
