<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expensename');
            $table->string('expensedetail');
            $table->decimal('expenseprice',16,0);
            $table->dateTime('expensedate')->nullable();
            $table->boolean('expenseactive',);
            $table->dateTime('expensecreatedat');
            $table->integer('expensecreatedby');
            $table->dateTime('expenseexecutedat')->nullable();
            $table->integer('expenseexecutedby')->nullable();
            $table->dateTime('expensemodifiedat')->nullable();
            $table->integer('expensemodifiedby')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenses');
    }
}
