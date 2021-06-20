<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo', function (Blueprint $table) {
            $table->id();
            $table->string('promoname');
            $table->string('promodetail')->nullable();
            $table->dateTime('promostart');
            $table->dateTime('promoend');
            $table->integer('promodiscount');
            $table->boolean('promoactive');
            $table->dateTime('promocreatedat');
            $table->integer('promocreatedby');
            $table->dateTime('promomodifiedat')->nullable();
            $table->integer('promomodifiedby')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promo');
    }
}
