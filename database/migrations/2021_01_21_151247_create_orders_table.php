<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('orderinvoice');
            $table->integer('orderinvoiceindex');
            $table->string('ordertype')->nullable();
            $table->string('ordercustname')->nullable();
            $table->dateTime('orderdate');
            $table->decimal('orderprice',16,0);
            $table->decimal('orderdp',16,0)->nullable();
            $table->dateTime('orderestdate')->nullable();
            $table->string('orderstatus');
            $table->string('orderdetail')->nullable();
            $table->string('orderpaymentmethod')->nullable();
            $table->boolean('orderpaid')->nullable();
            $table->decimal('orderpaidprice',16,0)->nullable();
            $table->decimal('orderremainingpaid',16,0)->nullable();
            $table->decimal('orderdiscountprice',16,0)->nullable();
            $table->string('orderpaidremark')->nullable();
            $table->bigInteger('orderpaidby')->nullable();
            $table->dateTime('orderpaidat')->nullable();
            $table->bigInteger('ordercompletedby')->nullable();
            $table->dateTime('ordercompleteddate')->nullable();
            $table->boolean('orderactive');
            $table->boolean('ordervoid')->nullable();
            $table->integer('ordervoidedby')->nullable();
            $table->dateTime('ordervoidedat')->nullable();
            $table->string('ordervoidreason')->nullable();
            $table->bigInteger('orderrefundid')->nullable();
            $table->dateTime('ordercreatedat');
            $table->integer('ordercreatedby');
            $table->dateTime('ordermodifiedat')->nullable();
            $table->integer('ordermodifiedby')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
