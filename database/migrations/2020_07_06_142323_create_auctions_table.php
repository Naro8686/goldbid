<?php

use App\Models\Auction\Auction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('short_desc')->nullable();
            $table->text('desc')->nullable();
            $table->text('specify')->nullable();
            $table->text('terms')->nullable();
            $table->string('img_1')->nullable();
            $table->string('img_2')->nullable();
            $table->string('img_3')->nullable();
            $table->string('img_4')->nullable();
            $table->string('alt_1')->nullable()->default('image');
            $table->string('alt_2')->nullable()->default('image');
            $table->string('alt_3')->nullable()->default('image');
            $table->string('alt_4')->nullable()->default('image');
            $table->string('start_price');
            $table->string('full_price');
            $table->string('bot_shutdown_price');
            $table->integer('bid_seconds');
            $table->timestamp('step_time')->nullable();
            $table->integer('step_price');
            $table->timestamp('start')->useCurrent();
            $table->timestamp('end')->nullable();
            $table->boolean('exchange');
            $table->boolean('top')->default(false);
            $table->boolean('active')->default(true);
            $table->integer('status')->default(Auction::STATUS_PENDING);
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('set null');
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
        Schema::dropIfExists('auctions');
    }
}
