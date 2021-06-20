<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShowcasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('showcases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('showcaseproductid');
            $table->string('showcasecode');
            $table->integer('showcaseqty');
            $table->dateTime('showcasedate');
            $table->dateTime('showcaseexpdate')->nullable();
            $table->string('showcasestatus');
            $table->boolean('showcaseactive');
            $table->dateTime('showcasecreatedat');
            $table->integer('showcasecreatedby');
            $table->dateTime('showcasemodifiedat')->nullable();
            $table->integer('showcasemodifiedby')->nullable();
            $table->dateTime('showcasesoldat')->nullable();
            $table->integer('showcasesoldby')->nullable();
            $table->dateTime('showcaseexpiredat')->nullable();
            $table->integer('showcaseexpiredby')->nullable();
            $table->integer('showcaseexpiredqty')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('showcases');
    }
}
