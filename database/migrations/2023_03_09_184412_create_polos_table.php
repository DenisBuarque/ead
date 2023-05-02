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
        Schema::create('polos', function (Blueprint $table) {
            $table->id();
            $table->string('title',100)->unique();
            $table->string('phone',16);
            $table->string('email',100);
            $table->string('zip_code',9)->nullable();
            $table->string('address',250);
            $table->string('city',50);
            $table->string('state',2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polos');
    }
};
