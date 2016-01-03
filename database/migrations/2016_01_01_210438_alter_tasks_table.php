<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('done', 'flag', 'due', 'label_id');
            
            $table->smallInteger('status')->default(0);
            
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
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('status', 'created_at', 'updated_at');
            
            $table->boolean('done')->default(false);
            $table->boolean('flag')->default(false);
            $table->date('due')->nullable();
            
            $table->integer('label_id')->unsigned()->nullable();
            $table->foreign('label_id')
                  ->references('id')->on('project_labels')
                  ->onDelete('set null');
        });
    }
}
