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
            $table->unsignedBigInteger('auction_id')->index()->nullable();
            $table->unsignedBigInteger('user_id')->index()->nullable();
            $table->integer('bet')->default(0)->index();
            $table->integer('bonus')->default(0)->index();
            $table->string('price')->nullable()->index();
            $table->string('title')->nullable();
            $table->string('nickname')->nullable()->index();
            $table->boolean('is_bot')->default(false)->index();
            $table->boolean('win')->default(false)->index();
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
