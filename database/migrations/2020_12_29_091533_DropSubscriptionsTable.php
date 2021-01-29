<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::dropIfExists('subscriptions');
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subscriber_id')->unsigned()->index();
            $table->integer('component_id')->unsigned()->index();
            $table->timestamps();
        });
    }
}
