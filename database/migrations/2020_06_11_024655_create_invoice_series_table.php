<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_series', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('organization_id')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->string('serie')->nullable();
            $table->integer('start_from')->nullable();
            $table->integer('current_number')->nullable();
            $table->boolean('status')->default(0);
            $table->foreign('organization_id')->references('id')->on('organizations');
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
        Schema::dropIfExists('invoice_series');
    }
}
