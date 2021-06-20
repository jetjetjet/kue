<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userroles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('uruserid');
            $table->bigInteger('urroleid');

            $table->boolean('uractive');
            $table->dateTime('urcreatedat');
            $table->integer('urcreatedby');
            $table->dateTime('urmodifiedat')->nullable();
            $table->integer('urmodifiedby')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('userroles');
    }
}
