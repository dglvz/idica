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
        Schema::create('info_medica', function (Blueprint $table) {
            $table->id('id_historia');
            $table->string('nombre_paciente');
            $table->string('cedula')->unique();
            $table->text('informacion')->nullable();
            $table->string('tipo_examen');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_medica');
    }
};
