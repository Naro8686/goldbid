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
            $table->enum('gender',[null,'male','female'])->default(null);
            $table->date('birthday')->nullable();
            $table->string('sms_code')->nullable();
            $table->timestamp('sms_verified_at')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('referral_link')->unique()->nullable();
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
