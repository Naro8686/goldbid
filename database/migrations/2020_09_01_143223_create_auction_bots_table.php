<?php

use App\Models\Bots\AuctionBot;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuctionBotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auction_bots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('auction_id');
            $table->unsignedBigInteger('bot_id');
            $table->string('name')->comment('bot 1,2,3');
            $table->string('time_to_bet')->comment('bot 1,2,3');
            $table->unsignedInteger('change_name')->nullable()->comment('bot 1');
            $table->unsignedInteger('num_moves')->nullable()->comment('bot 2,3');
            $table->unsignedInteger('num_moves_other_bot')->nullable()->comment('bot 2,3');
            $table->unsignedTinyInteger('status')->default(AuctionBot::PENDING);
            $table->foreign('auction_id')
                ->references('id')
                ->on('auctions')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('bot_id')
                ->references('id')
                ->on('bots')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
        Schema::dropIfExists('auction_bots');
    }
}
