<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement();
            $table->string('recipe_name');
            $table->string('title');
            $table->string('description');
            $table->string('recipe_picture');
            $table->string('ingredients');
            $table->string('nutritional_value');
            $table->double('cost',null,2);
            $table->string('primary_ingredients');
            $table->string('main_ingredients');
            $table->string('meal');
            $table->bigInteger('user_id')->nullable(true);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipes');
    }
}
