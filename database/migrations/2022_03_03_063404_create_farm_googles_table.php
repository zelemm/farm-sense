<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmGooglesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farm_googles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('farm_id')->nullable();
            $table->string('label')->nullable();

            $table->string('organisation_id')->nullable();
            $table->string('client_id')->nullable();
            $table->string('client_secret')->nullable();
            $table->string('timezone')->nullable();
            $table->longText('google_scopes')->nullable();

            $table->longText('code')->nullable();
            $table->longText('access_token')->nullable();
            $table->longText('refresh_token')->nullable();
            $table->longText('id_token')->nullable();
            $table->longText('token_type')->nullable();
            $table->longText('scope')->nullable();
            $table->longText('expires_in')->nullable();

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
        Schema::dropIfExists('farm_googles');
    }
}
