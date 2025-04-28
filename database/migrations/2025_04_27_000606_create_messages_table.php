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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->foreignId('user_from')->constrained('users')
                ->onDelete('cascade'); //id user logado
            $table->foreignId('user_to')->nullable()->constrained('users')
                ->onDelete('cascade'); //id user que recebe a mensagem
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_from', 'user_to']); // Melhora performance em buscas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
