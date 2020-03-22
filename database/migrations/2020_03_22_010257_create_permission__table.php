<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission', function (Blueprint $table) {
            $table->integer('source_role_id')->unsigned();
            $table->integer('interaction_id')->unsigned();
            $table->integer('target_role_id')->unsigned();
            // relations
            $table->foreign('source_role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');
            $table->foreign('interaction_id')
                ->references('id')
                ->on('interactions')
                ->onDelete('cascade');
            $table->foreign('target_role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');
            $table->unique([
                'source_role_id',
                'interaction_id',
                'target_role_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission');
    }
}
