<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpEmployeesIdToAllowedGroupsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('allowed_groups', function (Blueprint $table) {
            $table->integer('sp_employees_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('allowed_groups', function (Blueprint $table) {
            $table->dropColumn('sp_employees_id');
        });
    }
}
