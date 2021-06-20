<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('productpcid');
            $table->unsignedBigInteger('productrecipeid')->nullable();
            $table->string('productcode');
            $table->string('productname');
            $table->string('productimg')->nullable();
            $table->string('productdetail')->nullable();
            $table->decimal('productprice',16,0);
            $table->boolean('productactive');
            $table->dateTime('productcreatedat');
            $table->integer('productcreatedby');
            $table->dateTime('productmodifiedat')->nullable();
            $table->integer('productmodifiedby')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
