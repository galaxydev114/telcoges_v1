<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPmodelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_pmodel', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pmodel_id')->unsigned()->nullable();
            $table->bigInteger('client_id')->unsigned()->nullable();
            $table->bigInteger('organization_id')->unsigned()->nullable();
            $table->string('imei')->nullable();
            $table->foreign('pmodel_id')->references('id')->on('pmodels');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_pmodel');
    }
}
