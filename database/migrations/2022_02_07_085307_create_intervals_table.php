<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntervalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intervals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('interval_unit_id');
            $table->bigInteger('value');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('interval_unit_id')
                ->references('id')
                ->on('interval_units')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intervals');
    }
}
