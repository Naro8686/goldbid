<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mails', function (Blueprint $table) {
            $table->id();
            $table->string('driver',10)->nullable();
            $table->string('host',50)->nullable();
            $table->integer('port')->nullable()->unsigned();
            $table->string('from_address',150)->nullable();
            $table->string('from_name',150)->nullable();
            $table->string('encryption',10)->nullable();
            $table->string('username',150)->nullable();
            $table->string('password',150)->nullable();
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
        Schema::dropIfExists('mails');
    }
}
