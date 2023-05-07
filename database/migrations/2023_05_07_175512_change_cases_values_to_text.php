<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('test_cases', function (Blueprint $table) {
            $table->text('stdout')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('test_cases', function (Blueprint $table) {
            $table->string('stdout')->nullable()->change();
        });
    }
};
