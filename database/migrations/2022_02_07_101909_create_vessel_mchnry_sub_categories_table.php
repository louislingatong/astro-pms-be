<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVesselMchnrySubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vessel_mchnry_sub_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vessel_machinery_id');
            $table->string('code')->unique();
            $table->unsignedBigInteger('sub_category_id');
            $table->unsignedBigInteger('sub_category_description_id')->nullable();


            $table->timestamps();
            $table->softDeletes();

            $table->foreign('vessel_machinery_id')
                ->references('id')
                ->on('vessel_machineries')
                ->onDelete('cascade');
            $table->foreign('sub_category_id')
                ->references('id')
                ->on('sub_categories')
                ->onDelete('cascade');
            $table->foreign('sub_category_description_id')
                ->references('id')
                ->on('sub_category_descriptions')
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
        Schema::dropIfExists('vessel_mchnry_sub_categories');
    }
}
