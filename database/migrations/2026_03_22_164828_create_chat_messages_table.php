<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->string('session_id'); // Store the PHP session ID
            $table->enum('role', ['user', 'assistant']);
            $table->text('content');
            $table->timestamps();
            
            $table->index('session_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};