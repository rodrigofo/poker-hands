<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'moves',
            static function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('game_id');
                $table->foreign('game_id')->references('id')->on('games');
                $table->string('hand_1');
                $table->string('hand_2');

                $comment = <<<STR
 0 -> tie
 1 -> player 1 wins
-1 -> player 2 wins
STR;

                $table->enum('winner', [0, 1, -1])->comment($comment);
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('moves');
        Schema::enableForeignKeyConstraints();
    }
}
