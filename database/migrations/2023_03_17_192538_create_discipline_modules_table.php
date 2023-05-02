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
        Schema::create('discipline_modules', function (Blueprint $table) {
            $table->id();

            $table->string('title',200);
            $table->string('slug',255);
            $table->text('description')->nullable();
            $table->string('movie')->nullable();
            $table->string('file')->nullable();
            $table->set('category',['module','movie','file'])->default('module');

            $table->unsignedBigInteger('discipline_id');
            $table->foreign('discipline_id')->references('id')->on('disciplines')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discipline_modules');
    }
};
