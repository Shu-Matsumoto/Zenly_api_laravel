<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('friend_id');
            $table->double('latitude', 9, 7);
            $table->double('longitude', 10, 7);
            $table->timestamps();
        });

        DB::statement("ALTER TABLE " . DB::getTablePrefix() . "pins COMMENT 'ピン'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pins');
    }
}
