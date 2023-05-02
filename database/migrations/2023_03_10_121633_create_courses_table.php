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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title',100)->unique();
            $table->string('slug');
            $table->string('duration',50);
            $table->text('description');
            $table->set('institution',['setbal','ead'])->default('setbal');
            $table->set('status',['active','inactive'])->default('active');
            $table->string('image')->nullable();

            $table->unsignedBigInteger('polo_id');
            $table->foreign('polo_id')->references('id')->on('polos')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
