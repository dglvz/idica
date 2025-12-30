<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Crear tabla de Jobs si no existe
        if (!Schema::hasTable('jobs')) {
            Schema::create('jobs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('queue')->index();
                $table->longText('payload');
                $table->unsignedTinyInteger('attempts');
                $table->unsignedInteger('reserved_at')->nullable();
                $table->unsignedInteger('available_at');
                $table->unsignedInteger('created_at');
            });
        }

        // 2. Crear tabla de Failed Jobs si no existe
        if (!Schema::hasTable('failed_jobs')) {
            Schema::create('failed_jobs', function (Blueprint $table) {
                $table->id();
                $table->string('uuid')->unique();
                $table->text('connection');
                $table->text('queue');
                $table->longText('payload');
                $table->longText('exception');
                $table->timestamp('failed_at')->useCurrent();
            });
        }

        // 3. Agregar columna 'status' a info_medica si falta
        if (Schema::hasTable('info_medica') && !Schema::hasColumn('info_medica', 'status')) {
            Schema::table('info_medica', function (Blueprint $table) {
                $table->string('status')->default('pending')->after('orthanc_study_id');
            });
        }

        // 4. Agregar columna 'thumbnail' a imagenes si falta (para evitar el error anterior)
        if (Schema::hasTable('imagenes') && !Schema::hasColumn('imagenes', 'thumbnail')) {
             Schema::table('imagenes', function (Blueprint $table) {
                $table->string('thumbnail')->nullable()->after('ruta');
            });
        }
    }

    public function down()
    {
        // No revertimos nada para evitar pérdida de datos accidental
    }
};

