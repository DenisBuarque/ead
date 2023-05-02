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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->set('nivel',['admin','teacher','student']);
            $table->set('institution',['setbal','ead']);
            $table->integer('year')->nullable();
            $table->string('registration',50)->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone')->nullable();
            $table->string('image')->nullable();
            $table->string('password');
            $table->string('rg',30)->nullable();
            $table->string('cpf',14)->nullable();
            $table->string('filiation',100)->nullable();
            $table->set('sexo',['M','F'])->default('M');
            $table->date('date_entry')->nullable();
            $table->date('date_exit')->nullable();
            $table->string('conclusion',4)->nullable();
            $table->string('church',100)->nullable();
            $table->string('naturalness', 50)->nullable();
            $table->string('country',50)->nullable();
            $table->char('marital_status',1)->nullable();
            $table->set('access',['active','inactive'])->default('active');
            $table->date('birth_date')->nullable();
            $table->string('birthplace',50)->nullable();
            $table->string('zip_code',9)->nullable();
            $table->string('address',255)->nullable();
            $table->string('number',5)->nullable();
            $table->string('district',50)->nullable();
            $table->string('city',50)->nullable();
            $table->string('state',2)->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
