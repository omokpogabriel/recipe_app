<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_comments', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement();
            $table->text('admin_comment');
            $table->bigInteger('recipe_id');
            $table->timestamps();
            $table->foreign('recipe_id')->references('id')->on('recipes')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_comments');
    }
}
