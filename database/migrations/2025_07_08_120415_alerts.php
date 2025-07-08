<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('user_phone');
            $table->text('message')->nullable();
            $table->string('photo')->nullable();
            $table->string('video')->nullable(); // Novo campo para vídeo
            $table->string('audio')->nullable(); // Novo campo para áudio
            $table->string('location');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->enum('status', ['pending', 'in_progress', 'resolved'])->default('pending');
            $table->boolean('is_emergency')->default(false); // Para alertas de pânico
            $table->json('metadata')->nullable(); // Para dados adicionais
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('alerts');
    }
};