<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bots', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('number')->unique()->comment('bot 1,2,3');
            $table->boolean('is_active')->default(false)->comment('bot 1,2,3');
            $table->string('time_to_bet')->default(0)->comment('bot 1,2,3');
            $table->string('change_name')->comment('bot 1')->nullable();
            $table->string('num_moves')->comment('bot 2,3')->nullable();
            $table->string('num_moves_other_bot')->comment('bot 2,3')->nullable();
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
        Schema::dropIfExists('bots');
    }
}
