<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('submission_test_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_test_scenario_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_success');
            $table->string('std_out');
            $table->string('err_out');
            $table->json('messages');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('submission_test_cases');
    }
};
