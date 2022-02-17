<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVesselMachinerySubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vessel_machinery_sub_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->timestamp('due_date');
            $table->unsignedBigInteger('interval_id');
            $table->unsignedBigInteger('vessel_machinery_id');
            $table->unsignedBigInteger('machinery_sub_category_id');
            $table->unsignedBigInteger('machinery_sub_category_description_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('interval_id')
                ->references('id')
                ->on('intervals')
                ->onDelete('cascade');
            $table->foreign('vessel_machinery_id')
                ->references('id')
                ->on('vessel_machineries')
                ->onDelete('cascade');
            $table->foreign('machinery_sub_category_id', 'vsl_mchnry_sub_cat_mchnry_sub_cat_id_foreign')
                ->references('id')
                ->on('machinery_sub_categories')
                ->onDelete('cascade');
            $table->foreign('machinery_sub_category_description_id', 'vsl_mchnry_sub_cat_mchnry_sub_cat_desc_id_foreign')
                ->references('id')
                ->on('machinery_sub_category_descriptions')
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
        Schema::dropIfExists('vessel_machinery_sub_categories');
    }
}
