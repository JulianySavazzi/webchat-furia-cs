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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('owner_id')
                ->constrained('users'); //furia_bot inicialmente
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('team_id')->nullable()->constrained('teams')
                ->onDelete('cascade'); //id grupo do fruria_bot inicialmente
        });

        Schema::create('teams_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users');
            $table->foreignId('team_id')
                ->constrained('teams');
            $table->foreignId('message_id')->nullable()
                ->constrained('messages');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign('messages_team_id_foreign');
            $table->dropColumn('team_id');
        });
        Schema::dropIfExists('teams');
    }
};
