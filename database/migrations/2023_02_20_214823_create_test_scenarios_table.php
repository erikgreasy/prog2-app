<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('test_scenarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->unsignedFloat('points');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_scenarios');
    }
};
