<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('text_in_submission_cases', function (Blueprint $table) {
            $table->text('actual_stdout')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('text_in_submission_cases', function (Blueprint $table) {
            $table->string('actual_stdout')->nullable()->change();
        });
    }
};
