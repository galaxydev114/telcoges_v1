<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('custom_id')->unsigned();
            $table->bigInteger('client_id')->unsigned();
            $table->bigInteger('organization_id')->unsigned();
            $table->date('date');
            $table->string('comment')->nullable();
            $table->double('iva_rate')->default(21);
            $table->double('total');
            $table->double('iva');
            $table->double('grand_total');
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
        Schema::dropIfExists('budgets');
    }
}
