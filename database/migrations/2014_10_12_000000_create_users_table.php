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
            $table->string('name');
            $table->string('email')->unique();
            $table->text('password');
            $table->string('image',255)->nullable();
            $table->string('type',1);
            $table->string('phone',20);
            $table->string('address',255)->nullable();
            $table->date('dob')->nullable();
            // $table->integer('create_user_id');
            // $table->integer('updated_user_id');
            // $table->integer('deleted_user_id');
            // $table->timestamp('deleted_at');
            $table->timestamp('email_verified_at')->nullable();
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
