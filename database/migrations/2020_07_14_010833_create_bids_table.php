<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('auction_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('bet')->default(0);
            $table->integer('bonus')->default(0);
            $table->string('price')->nullable();
            $table->string('title')->nullable();
            $table->string('nickname')->nullable();
            $table->boolean('is_bot')->default(false);
            $table->boolean('win')->default(false);
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
        Schema::dropIfExists('bids');
    }
}
