<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVesselsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vessels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vessel_owner_id');
            $table->string('code_name');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('vessel_owner_id')
                ->references('id')
                ->on('vessel_owners')
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
        Schema::dropIfExists('vessels');
    }
}
