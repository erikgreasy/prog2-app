<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->after('role', function(Blueprint $table) {
                $table->string('github_access_token')->nullable();
                $table->string('github_repo')->nullable();
            });
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('github_access_token');
            $table->dropColumn('github_repo');
        });
    }
};
