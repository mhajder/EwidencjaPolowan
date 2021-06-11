<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHuntedAnimalsTable extends Migration
{
    /**we
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hunted_animals', function (Blueprint $table) {
            $table->id();
            $table->integer('hunting_book_id')->index();
            $table->integer('animal_category_id');
            $table->integer('animal_id');
            $table->integer('purpose');
            $table->string('tag');
            $table->integer('weight');
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
        Schema::dropIfExists('hunted_animals');
    }
}
