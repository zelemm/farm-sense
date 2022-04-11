<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCattleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cattles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('farm_id');
            $table->string('mac_id')->nullable();
            $table->string('cattle_type')->nullable();
            $table->json('images')->nullable();
            $table->string('name')->nullable();
            $table->string('arrival')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('date_of_death')->nullable();
            $table->date('date_of_sell')->nullable();
            $table->string('breed')->nullable();
            $table->string('sex')->nullable();
            $table->string('weight')->nullable();
            $table->string('casterated')->nullable();
            $table->string('vendor')->nullable();
            $table->double('purchase_price')->nullable();
            $table->double('sold_price')->nullable();

            $table->integer('mother_cow')->nullable();
            $table->integer('father_cow')->nullable();

            $table->string('status')->nullable();
            $table->longText('notes')->nullable();

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
        Schema::dropIfExists('cattles');
    }
}
