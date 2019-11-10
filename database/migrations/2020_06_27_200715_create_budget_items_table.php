<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('budget_id')->unsigned();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('quantity');
            $table->string('price');
            $table->double('tax_rate');
            $table->double('total');
            $table->double('tax');
            $table->double('grand_total');
            $table->foreign('budget_id')->references('id')->on('budgets');
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
        Schema::dropIfExists('budget_items');
    }
}
