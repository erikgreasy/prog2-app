<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->after('report', function (Blueprint $table) {
                $table->boolean('build_status')->nullable();
                $table->json('gcc_warning')->nullable();
                $table->json('gcc_error')->nullable();
            });
        });
    }

    public function down()
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn(['build_status', 'gcc_error', 'gcc_warning']);
        });
    }
};
