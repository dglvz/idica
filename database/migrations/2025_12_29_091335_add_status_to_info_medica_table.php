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
            if (!Schema::hasColumn('info_medica', 'status')) {
                $table->string('status')->default('pending')->after('orthanc_study_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('info_medica', function (Blueprint $table) {
            if (Schema::hasColumn('info_medica', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
