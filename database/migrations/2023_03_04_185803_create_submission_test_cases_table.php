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
            $table->string('cmdin')->nullable();
            $table->string('stdin')->nullable();
            $table->string('stdout')->nullable();
            $table->string('errout')->nullable();
            $table->string('actual_stdout')->nullable();
            $table->string('actual_stderr')->nullable();
            $table->json('messages');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('submission_test_cases');
    }
};
