<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title',255)->unique();
            $table->string('description');
            // $table->integer('status');
            //$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('user_id');
            // $table->integer('create_user_id')->foreignId();
            // $table->integer('updated_user_id')->foreignId();
            // $table->integer('deleted_user_id');
            // $table->timestamp('deleted_at');
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
        Schema::dropIfExists('posts');
    }
}



