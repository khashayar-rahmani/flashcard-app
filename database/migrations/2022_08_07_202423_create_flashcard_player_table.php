<?php

use App\Constants\FlashcardGameConstants;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flashcard_player', function (Blueprint $table) {
            $table->id();
            $table->string('practice_status')->nullable(true)->default(FlashcardGameConstants::STATUS_NOT_ANSWERED);
            $table->string('player_answer')->nullable(true)->default(null);
            $table->unsignedBigInteger('flashcard_id');
            $table->foreign('flashcard_id')->references('id')->on('flashcards')->onDelete('cascade');
            $table->unsignedBigInteger('player_id');
            $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');
            $table->unique(['flashcard_id', 'player_id']);
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
        Schema::dropIfExists('flashcard_player');
    }
};
