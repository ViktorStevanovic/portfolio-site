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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('job_title');
            $table->string('tagline')->nullable();
            $table->text('bio');
            $table->string('photo')->nullable();
            $table->string('cv_path')->nullable();
            $table->integer('cv_downloads')->default(0);
            $table->string('github_url');
            $table->string('linkedin_url');
            $table->string('email_public');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
