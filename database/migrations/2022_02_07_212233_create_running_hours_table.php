<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRunningHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('running_hours', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vessel_machinery_id');
            $table->decimal('running_hours');
            $table->timestamp('updating_date');
            $table->unsignedBigInteger('creator_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('vessel_machinery_id')
                ->references('id')
                ->on('vessel_machineries')
                ->onDelete('cascade');
            $table->foreign('creator_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('running_hours');
    }
}
