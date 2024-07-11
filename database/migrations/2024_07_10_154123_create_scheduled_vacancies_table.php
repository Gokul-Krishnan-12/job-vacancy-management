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
        Schema::create('scheduled_vacancies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vacancy_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->enum('status', ['scheduled', 'cancelled'])->default('scheduled');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_vacancies');
    }
};
