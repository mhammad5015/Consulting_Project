<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experts', function (Blueprint $table) {
            $table->id("expert_id");
            $table->string("name");
            $table->string('email')->unique();
            $table->string('password');
            $table->string('img');
            $table->string('phone_num');
            $table->string('address');
            $table->text('experience');
            $table->float('expert_wallet');
            $table->smallInteger('session_time');
            $table->float('session_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('experts');
    }
};
