<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menumcid');
            $table->string('menuname');
            $table->string('menutype');
            $table->string('menuimg')->nullable();
            $table->string('menudetail')->nullable();
            $table->decimal('menuprice',16,0);
            $table->boolean('menuavaible');
            $table->boolean('menuactive');
            $table->dateTime('menucreatedat');
            $table->integer('menucreatedby');
            $table->dateTime('menumodifiedat')->nullable();
            $table->integer('menumodifiedby')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
