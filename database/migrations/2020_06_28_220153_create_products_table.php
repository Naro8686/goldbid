<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
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
            $table->string('start_price')->default(1)->comment('начальная цена (руб)');
            $table->string('full_price')->default(1)->comment('полная стоимость (руб)');
            $table->string('bot_shutdown_price')->default(1)->comment('цена выключения бота (руб)');
            $table->integer('step_time')->default(10)->comment('сек.');
            $table->integer('step_price')->default(10);
            $table->integer('to_start')->default(0)->comment('мин.');
            $table->boolean('exchange')->default(true);
            $table->boolean('buy_now')->default(true);
            $table->boolean('top')->default(false);
            $table->boolean('visibly')->default(true);
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('set null');
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
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
        Schema::dropIfExists('products');
    }
}
