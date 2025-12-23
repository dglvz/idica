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
        Schema::table('info_medica', function (Blueprint $table) {
            // Agregamos el campo para el ID del estudio de Orthanc
            // Lo hacemos nullable porque no todas las historias médicas tendrán imágenes DICOM
            $table->string('orthanc_study_id')->nullable()->after('tipo_examen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('info_medica', function (Blueprint $table) {
            $table->dropColumn('orthanc_study_id');
        });
    }
};