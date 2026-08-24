<?php

use App\Models\User;
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
        Schema::create('intra_person', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('cpf')->nullable(true)->unique();
            $table->date('birthdate')->nullable(true);
            $table->string('email')->nullable(true);
            $table->string('phone')->nullable(true);
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intra_person_id')->nullable()->constrained('intra_person')->onDelete('cascade');
            $table->string('login');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        User::factory()->make();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('users');
        Schema::dropIfExists('intra_person');
    }
};
