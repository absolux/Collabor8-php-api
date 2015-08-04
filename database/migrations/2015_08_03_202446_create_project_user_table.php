<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_user', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                  ->references('id')->on('users');
            
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')
                  ->references('id')->on('projects')
                  ->onDelete('cascade');
            
            $table->string('role', 30)->default('member');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('project_user');
    }
}
