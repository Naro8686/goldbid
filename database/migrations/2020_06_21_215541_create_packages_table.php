<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //PackageController
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('alt')->nullable()->default('image');
            $table->integer('bet')->unsigned()->nullable()->default(0);
            $table->integer('bonus')->unsigned()->nullable()->default(0);
            $table->integer('price')->unsigned()->nullable()->default(0);
            $table->boolean('visibly')->default(true);
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
        Schema::dropIfExists('packages');
    }
}
