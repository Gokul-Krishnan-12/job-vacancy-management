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
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id'); 
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('description');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('job_id')->references('id')->on('jobs_table')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancies');
    }
};
