<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserGroupIdToComponentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('components', function (Blueprint $table) {
            $table->integer('user_groups_id')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('components', function (Blueprint $table) {
            $table->dropColumn('user_groups_id');
        });
    }
}
