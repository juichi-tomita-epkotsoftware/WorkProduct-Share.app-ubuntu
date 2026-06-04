<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up():void
    {
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->string('name'); //住民の名前
            $table->string('job');  //住民の職業
            $table->string('likes');  //住民の好きなもの
            $table->string('dislikes');  //住民の苦手なもの
            $table->string('birthplace');  //住民の出身地
            $table->integer('age');  //住民の出身地
            $table->string('image_path');  //住民の出身地
            $table->string('bio',500);  //住民の出身地
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
        Schema::dropIfExists('residents');
    }
};
