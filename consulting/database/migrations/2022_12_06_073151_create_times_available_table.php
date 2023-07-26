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
        Schema::create('times_available', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("expert_id");
            $table->foreign("expert_id")->references("expert_id")->on("experts");
            $table->integer("day");
            $table->integer("from");
            $table->integer("to");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('days_available');
    }
};
