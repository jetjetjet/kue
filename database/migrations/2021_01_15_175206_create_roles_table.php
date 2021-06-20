<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('rolename');
            $table->string('roledetail')->nullable();
            $table->text('rolepermissions');
            $table->boolean('roleisadmin');

            $table->boolean('roleactive');
            $table->dateTime('rolecreatedat');
            $table->integer('rolecreatedby');
            $table->dateTime('rolemodifiedat')->nullable();
            $table->integer('rolemodifiedby')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
