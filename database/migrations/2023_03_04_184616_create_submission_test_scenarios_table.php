<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('submission_test_scenarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_scenario_id')->constrained();
            $table->foreignId('submission_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('submission_test_scenarios');
    }
};
