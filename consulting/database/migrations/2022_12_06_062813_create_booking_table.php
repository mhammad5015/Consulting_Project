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
        Schema::create('booking', function (Blueprint $table) {
            $table->unsignedBigInteger('expert_id');
            $table->foreign("expert_id")->references("expert_id")->on("experts");

            $table->unsignedBigInteger('user_id');
            $table->foreign("user_id")->references("user_id")->on("users");

            $table->smallInteger("day");
            $table->smallInteger("from");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consults');
    }
};
