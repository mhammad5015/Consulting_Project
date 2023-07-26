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
        Schema::create('expert_consultations', function (Blueprint $table) {

            $table->unsignedBigInteger('expert_id');
            $table->foreign("expert_id")->references("expert_id")->on("experts");

            $table->unsignedBigInteger('consult_id');
            $table->foreign("consult_id")->references("consult_id")->on("consults_types");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expert_consultations');
    }
};
