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
            $table->string('former_name')->nullable();
            $table->string('flag')->nullable();
            $table->string('call_sign')->nullable();
            $table->string('official_no')->nullable();
            $table->string('imo_no')->nullable();
            $table->string('loa')->nullable();
            $table->string('lbp')->nullable();
            $table->string('light_condition')->nullable();
            $table->string('classification')->nullable();
            $table->string('character')->nullable();
            $table->string('descriptive_note')->nullable();
            $table->string('built_year')->nullable();
            $table->string('build_yard')->nullable();
            $table->string('tpc')->nullable();
            $table->string('breadth')->nullable();
            $table->string('depth')->nullable();
            $table->string('summer_draft')->nullable();
            $table->string('summer_freeboard')->nullable();
            $table->string('summer_deadweight')->nullable();
            $table->string('winter_draft')->nullable();
            $table->string('winter_freeboard')->nullable();
            $table->string('winter_deadweight')->nullable();
            $table->string('tropical_draft')->nullable();
            $table->string('tropical_freeboard')->nullable();
            $table->string('tropical_deadweight')->nullable();
            $table->string('tropical_fw_draft')->nullable();
            $table->string('tropical_fw_freeboard')->nullable();
            $table->string('tropical_fw_deadweight')->nullable();
            $table->string('fw_draft')->nullable();
            $table->string('fw_freeboard')->nullable();
            $table->string('fw_deadweight')->nullable();
            $table->string('fw_allowance')->nullable();
            $table->string('light_shift_drafts_f')->nullable();
            $table->string('light_shift_drafts_a')->nullable();
            $table->string('heavy_ballast_drafts_f')->nullable();
            $table->string('heavy_ballast_drafts_a')->nullable();
            $table->string('normal_ballast_drafts_f')->nullable();
            $table->string('normal_ballast_drafts_a')->nullable();
            $table->string('international_gt')->nullable();
            $table->string('international_nt')->nullable();
            $table->string('panama_gt')->nullable();
            $table->string('panama_nt')->nullable();
            $table->string('suez_gt')->nullable();
            $table->string('suez_nt')->nullable();
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
