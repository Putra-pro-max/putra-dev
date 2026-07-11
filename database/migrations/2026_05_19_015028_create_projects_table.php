<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::create('projects', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description');
        $table->string('image')->nullable();
        $table->string('visit_url')->nullable();
        $table->string('github_url')->nullable();
        $table->json('tags')->nullable(); // ['Laravel', 'Tailwind', dll]
        $table->boolean('is_featured')->default(true);
        $table->integer('order')->default(0);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
