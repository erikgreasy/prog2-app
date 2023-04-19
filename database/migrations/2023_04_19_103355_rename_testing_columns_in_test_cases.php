<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('test_cases', function (Blueprint $table) {
            $table->renameColumn('cmd_in', 'cmdin');
            $table->renameColumn('std_in', 'stdin');
            $table->renameColumn('std_out', 'stdout');
            $table->renameColumn('err_out', 'errout');
        });
    }

    public function down()
    {
        Schema::table('test_cases', function (Blueprint $table) {
            $table->renameColumn('cmdin', 'cmd_in');
            $table->renameColumn('stdin', 'std_in');
            $table->renameColumn('stdout', 'std_out');
            $table->renameColumn('errout', 'err_out');
        });
    }
};
