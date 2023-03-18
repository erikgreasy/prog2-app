<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('submission_test_scenarios', function (Blueprint $table) {
            $table->decimal('points')->after('submission_id');
        });
    }

    public function down()
    {
        Schema::table('submission_test_scenarios', function (Blueprint $table) {
            $table->dropColumn('points');
        });
    }
};
