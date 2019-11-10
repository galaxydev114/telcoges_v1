<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('doc_number');
            $table->bigInteger('client_id')->unsigned();
            $table->date('date');
            $table->date('due_date');
            $table->string('type')->default('sell');
            $table->string('comment')->nullable();
            $table->string('pay_way');
            $table->string('status')->default('pending');
            $table->double('iva_rate')->default(21);
            $table->double('total');
            $table->double('iva');
            $table->double('grand_total');
            $table->foreign('client_id')->references('id')->on('clients');
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
        Schema::dropIfExists('invoices');
    }
}
