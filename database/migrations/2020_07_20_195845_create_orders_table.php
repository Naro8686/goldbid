<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_num')->unique()->nullable();
            $table->string('payment_type')->nullable();
            $table->string('price')->nullable();
            $table->enum('status', [0, 1, 2])->default(\App\Models\Auction\Order::PENDING);
            $table->boolean('exchanged')->default(false);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('auction_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('auction_id')
                ->references('id')
                ->on('auctions')
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
        Schema::dropIfExists('orders');
    }
}
