<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('users')){
            return;
        }
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->comment('主キー');
            $table->string('name')->nullable(false)->comment('名前');
            $table->integer('age')->length(3)->nullable(false)->comment('年齢');
            $table->string('email',180)->unique()->nullable(false)->comment('メールアドレス');
            $table->string('password')->nullable(false)->comment('パスワード');
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
