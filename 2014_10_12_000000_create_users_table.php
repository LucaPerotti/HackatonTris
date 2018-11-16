<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(/**
         * @param Blueprint $table
         */
            'users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('wins')->nullable();
            $table->integer('losses')->nullable();
            $table->integer('draws')->nullable();
            $table->integer('score')->nullable();
            $table->rememberToken();
            $table->timestamps();
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


/***
 * Personal access client created successfully.
Client ID: 1
Client Secret: OXfhTsiwpK8F9U6PHMBjOdaNaxeNTz45BGBKzAfZ
Password grant client created successfully.
Client ID: 2
Client Secret: L7oLUA5YRUwOxF2Yd8f3z82lg9hz0Q8bjvRWS7E4

 */