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
        Schema::create('person', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('cpf')->nullable(true)->unique();
            $table->string('cnpj')->nullable(true)->unique();
            $table->date('birthdate')->nullable(true);
            $table->string('email')->nullable(true);
            $table->string('phone')->nullable(true);
            $table->timestamps();
        });

        Schema::create('address', function (Blueprint $table) {
            $table->id();
            $table->string('street');
            $table->string('number');
            $table->string('neighborhood');
            $table->string('city');
            $table->string('state');
            $table->string('zip_code');
            $table->timestamps();
        });
        
        Schema::create('costumer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->nullable(false)->constrained('person')->onDelete('cascade');
            $table->foreignId('address_id')->nullable(true)->constrained('address')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('supplier', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->nullable(false)->constrained('person')->onDelete('cascade');
            $table->foreignId('address_id')->nullable(true)->constrained('address')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('costumer');
        Schema::dropIfExists('supplier');
        Schema::dropIfExists('address');
        Schema::dropIfExists('person');
    }
};
