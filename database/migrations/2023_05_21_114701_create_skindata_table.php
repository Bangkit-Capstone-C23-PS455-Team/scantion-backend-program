<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkindataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('skindata', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->date('date');
            $table->string('user_id');
            $table->string('bodypart');
            $table->date('since');
            $table->string('symptom',255);
            $table->string('cancertype');
            $table->float('accu');
            $table->string('link');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('skindata');
    }
}
