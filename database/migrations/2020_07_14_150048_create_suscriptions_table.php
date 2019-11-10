<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suscriptions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('organization_id')->unsigned()->nullable();
            $table->bigInteger('membership_id')->unsigned()->nullable();
            $table->double('price');
            $table->double('iva');
            $table->boolean('status')->default(1);
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->foreign('membership_id')->references('id')->on('memberships');
            $table->softdeletes();
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
        Schema::dropIfExists('suscriptions');
    }
}
