<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('test_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_scenario_id')->constrained()->cascadeOnDelete();
            $table->string('cmd_in');
            $table->string('std_in');
            $table->string('std_out');
            $table->string('err_out');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_cases');
    }
};
