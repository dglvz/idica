<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('imagenes', function (Blueprint $table) {
        $table->unsignedBigInteger('info_medica_id')->nullable()->after('paciente_id');
        $table->foreign('info_medica_id')->references('id_historia')->on('info_medica')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('imagenes', function (Blueprint $table) {
        $table->dropForeign(['info_medica_id']);
        $table->dropColumn('info_medica_id');
    });
}
};
