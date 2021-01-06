<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableAllowedGroups extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('allowed_groups', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('users_id');
            $table->integer('user_groups_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('allowed_groups');
    }
}
