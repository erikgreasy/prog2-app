<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('test_cases', function (Blueprint $table) {
            $table->string('gcc_macro_defs')->nullable()->after('test_scenario_id');
        });
    }

    public function down()
    {
        Schema::table('test_cases', function (Blueprint $table) {
            $table->dropColumn('gcc_macro_defs');
        });
    }
};
