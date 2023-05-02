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
        Schema::create('direct_chat_messages', function (Blueprint $table) {
            $table->id();

            $table->string('message',255);
            $table->boolean('check_admin');
            $table->boolean('check_student');

            $table->unsignedBigInteger('direct_chat_id');
            $table->foreign('direct_chat_id')->references('id')->on('direct_chats')->onDelete('cascade');
            
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
        Schema::dropIfExists('direct_chat_messages');
    }
};
