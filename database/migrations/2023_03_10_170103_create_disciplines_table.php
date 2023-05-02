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
        Schema::create('disciplines', function (Blueprint $table) {
            $table->id();
            $table->set('institution',['setbal','ead'])->default('setbal');
            $table->string('title',100);
            $table->string('slug');
            $table->integer('year');
            $table->integer('semester');
            $table->string('workload',50)->nullable();
            $table->string('period',50)->nullable();
            $table->string('credits',50)->nullable();
            $table->text('description');
            $table->set('quiz',['active','inactive'])->default('inactive');
            $table->set('status',['active','inactive'])->default('inactive');
            
            $table->unsignedBigInteger('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disciplines');
    }
};
