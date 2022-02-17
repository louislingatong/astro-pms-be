<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMachinerySubCategoryDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machinery_sub_category_descriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('machinery_sub_category_id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('machinery_sub_category_id', 'mchnry_sub_cat_desc_mchnry_sub_cat_id_foreign')
                ->references('id')
                ->on('machinery_sub_categories')
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
        Schema::dropIfExists('machinery_sub_category_descriptions');
    }
}
