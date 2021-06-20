<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menucategory', function (Blueprint $table) {
            $table->id();
            $table->string('mcname',100);
            $table->boolean('mcactive');
            $table->dateTime('mccreatedat');
            $table->integer('mccreatedby');
            $table->dateTime('mcmodifiedat')->nullable();
            $table->integer('mcmodifiedby')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_categories');
    }
}
