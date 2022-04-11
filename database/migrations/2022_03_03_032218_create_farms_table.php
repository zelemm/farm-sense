<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->json('images')->nullable();

            $table->longText('address')->nullable();
            $table->longText('phone')->nullable();

            $table->longText('api_key')->nullable();
            $table->longText('lat')->nullable();
            $table->longText('lng')->nullable();

            $table->longText('notes')->nullable();
            $table->string('status')->nullable();

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
        Schema::dropIfExists('farms');
    }
}
