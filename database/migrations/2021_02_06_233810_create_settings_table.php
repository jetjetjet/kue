<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('settingcategory',25);
            $table->string('settingkey',200);
            $table->string('settingvalue',200)->nullable();
            $table->boolean('settingactive',);
            $table->dateTime('settingcreatedat');
            $table->integer('settingcreatedby');
            $table->dateTime('settingmodifiedat')->nullable();
            $table->integer('settingmodifiedby')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
