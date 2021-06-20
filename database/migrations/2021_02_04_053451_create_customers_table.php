<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('custname');
            $table->text('custaddress')->nullable();
            $table->string('custphone')->nullable();
            $table->boolean('custactive');
            $table->dateTime('custcreatedat');
            $table->integer('custcreatedby');
            $table->dateTime('custmodifiedat')->nullable();
            $table->integer('custmodifiedby')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
