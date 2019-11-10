<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('suscription_id')->unsigned();
            $table->string('transaction_id');
            $table->double('sub_total');
            $table->double('tax');
            $table->double('tax_rate');
            $table->string('status')->default('pending');
            $table->date('date_from');
            $table->date('date_to');
            $table->foreign('suscription_id')->references('id')->on('suscriptions');
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
        Schema::dropIfExists('payments');
    }
}
