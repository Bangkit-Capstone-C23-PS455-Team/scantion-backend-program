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
             $table->string('id')->primary()->default(Str::random(10));
             $table->string('name', 100);
             $table->string('email', 191)->unique();
             $table->string('password', 100);
             $table->integer('age');
             $table->string('province', 100);
             $table->string('city', 100);
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

    public function boot()
    {
    Schema::defaultStringLength(191);
    }

}
