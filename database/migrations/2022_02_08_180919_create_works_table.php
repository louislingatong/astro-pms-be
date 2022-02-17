<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('works', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vessel_machinery_sub_category_id');
            $table->timestamp('last_done');
            $table->string('instructions')->nullable();
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('creator_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('vessel_machinery_sub_category_id')
                ->references('id')
                ->on('vessel_machinery_sub_categories')
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
        Schema::dropIfExists('works');
    }
}
