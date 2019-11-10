<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->string('name');
            $table->string('nif');
            $table->string('type')->default('person');
            $table->string('address');
            $table->string('population');
            $table->string('postal_code');
            $table->string('province');
            $table->string('country');
            $table->string('commercial_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('celphone')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
