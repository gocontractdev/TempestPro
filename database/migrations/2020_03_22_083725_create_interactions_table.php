<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInteractionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interactions', function (Blueprint $table) {
            $table->integer('source_role_id')->unsigned();
            $table->integer('permission_id')->unsigned();
            $table->integer('target_role_id')->unsigned();
            // relations
            $table->foreign('source_role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');
            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');
            $table->foreign('target_role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');
            $table->unique([
                'source_role_id',
                'permission_id',
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
        Schema::dropIfExists('interactions');
    }
}
