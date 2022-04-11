<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmFenceCoordinatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farm_fence_coordinates', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('farm_fence_id');
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();

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
        Schema::dropIfExists('farm_fence_coordinates');
    }
}
