<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsedHuntingGroundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('used_hunting_grounds', function (Blueprint $table) {
            $table->integer('hunting_book_id');
            $table->integer('hunting_ground_id');
            $table->primary(['hunting_ground_id', 'hunting_book_id']);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('used_hunting_grounds');
    }
}
