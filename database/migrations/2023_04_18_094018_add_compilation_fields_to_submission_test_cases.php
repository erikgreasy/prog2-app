<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('submission_test_cases', function (Blueprint $table) {
            $table->after('submission_test_scenario_id', function (Blueprint $table) {
                $table->boolean('build_status');
                $table->json('gcc_warnings');
                $table->json('gcc_errors');
            });
        });
    }

    public function down()
    {
        Schema::table('submission_test_cases', function (Blueprint $table) {
            $table->dropColumn([
                'build_status',
                'gcc_warnings',
                'gcc_errors',
            ]);
        });
    }
};
