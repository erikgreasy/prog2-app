<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('submission_test_cases', function (Blueprint $table) {
            $table->text('actual_stderr')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('submission_test_cases', function (Blueprint $table) {
            $table->string('actual_stderr')->nullable()->change();
        });
    }
};
