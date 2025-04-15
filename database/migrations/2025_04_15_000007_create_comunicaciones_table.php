<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comunicaciones', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('mensaje');
            $table->dateTime('fecha_envio');
            $table->foreignId('curso_id')->nullable()->constrained('cursos')->onDelete('cascade');
            $table->foreignId('padre_id')->nullable()->constrained('padres')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comunicaciones');
    }
};
