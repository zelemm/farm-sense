<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmFencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farm_fences', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('farm_id');
            $table->string('label')->nullable();
            $table->string('description')->nullable();
            $table->string('center_lng')->nullable();
            $table->string('center_lat')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->integer('added_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farm_fences');
    }
}
