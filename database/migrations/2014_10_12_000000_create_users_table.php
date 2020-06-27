<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_admin')->default(false);
            $table->boolean('has_ban')->default(false);
            $table->string('nickname')->unique();
            $table->string('avatar')->nullable();
            $table->string('fname',50)->nullable();
            $table->string('lname',100)->nullable();
            $table->string('mname',60)->nullable();
            $table->string('phone')->unique();
            $table->string('postcode',15)->nullable();
            $table->string('region')->nullable();
            $table->string('city')->nullable();
            $table->string('street')->nullable();
            $table->enum('gender',['male','female'])->default('male')->nullable();
            $table->date('birthday')->nullable();
            $table->string('sms_code')->nullable();
            $table->timestamp('sms_verified_at')->nullable();
            $table->string('email')->unique()->nullable();
            $table->integer('email_code')->nullable()->index();
            $table->timestamp('email_code_verified')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->timestamp('is_online')->default(now());
            $table->unsignedTinyInteger('payment_type')->nullable();
            $table->unsignedInteger('ccnum')->nullable();
            $table->unsignedBigInteger('referred_by')->nullable();
            $table->foreign('referred_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
