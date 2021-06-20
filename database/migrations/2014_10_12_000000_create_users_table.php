<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('userfullname');
            $table->string('username')->unique();
            $table->string('userpassword');
            $table->string('usercontact')->nullable();
            $table->string('useraddress')->nullable();
            $table->dateTime('userjoindate')->nullable();
            $table->boolean('useractive');
            $table->dateTime('usercreatedat');
            $table->integer('usercreatedby');
            $table->dateTime('usermodifiedat')->nullable();
            $table->integer('usermodifiedby')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
