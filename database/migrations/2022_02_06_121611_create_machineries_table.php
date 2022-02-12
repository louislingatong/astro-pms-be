<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMachineriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machineries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vessel_department_id');
            $table->string('code_name')->unique();
            $table->string('name');
            $table->unsignedBigInteger('machinery_model_id')->nullable();
            $table->unsignedBigInteger('machinery_maker_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('vessel_department_id')
                ->references('id')
                ->on('vessel_departments')
                ->onDelete('cascade');
            $table->foreign('machinery_model_id')
                ->references('id')
                ->on('machinery_models')
                ->onDelete('cascade');
            $table->foreign('machinery_maker_id')
                ->references('id')
                ->on('machinery_makers')
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
        Schema::dropIfExists('machineries');
    }
}
