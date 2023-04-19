<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('test_cases', function (Blueprint $table) {
            $table->string('cmd_in')->nullable()->change();
            $table->string('std_in')->nullable()->change();
            $table->string('std_out')->nullable()->change();
            $table->string('err_out')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('test_cases', function (Blueprint $table) {
            $table->string('cmd_in')->change();
            $table->string('std_in')->change();
            $table->string('std_out')->change();
            $table->string('err_out')->change();
        });
    }
};
