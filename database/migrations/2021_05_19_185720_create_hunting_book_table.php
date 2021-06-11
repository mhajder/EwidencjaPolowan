<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHuntingBookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hunting_book', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->index();
            $table->integer('authorization_id');
            $table->integer('district_id');
            $table->integer('hunting_id');
            $table->datetime('start');
            $table->datetime('end');
            $table->integer('shots')->default('0');
            $table->text('description')->nullable();
            $table->boolean('canceled')->default(false);
            $table->softDeletes();
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
        Schema::dropIfExists('hunting_book');
    }
}
