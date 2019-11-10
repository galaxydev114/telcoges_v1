<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepairsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repairs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id')->unsigned()->nullable();
            $table->bigInteger('client_pmodel_id')->unsigned()->nullable();
            $table->bigInteger('organization_id')->unsigned()->nullable();
            
            $table->string('condition')->nullable();
            $table->datetime('date');
            $table->string('repair');
            $table->double('price');
            $table->string('note');
            $table->string('anotation');
            $table->string('private_anotation');
            $table->string('status')->default('received');

            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('client_pmodel_id')->references('id')->on('client_pmodel');
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
        Schema::dropIfExists('repairs');
    }
}
