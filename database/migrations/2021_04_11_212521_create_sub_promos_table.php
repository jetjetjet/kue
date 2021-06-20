<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubPromosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subpromo', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sppromoid');
            $table->bigInteger('spproductid');
            $table->integer('spindex');
            $table->boolean('spactive');
            $table->dateTime('spcreatedat');
            $table->integer('spcreatedby');
            $table->dateTime('spmodifiedat')->nullable();
            $table->integer('spmodifiedby')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subpromo');
    }
}
