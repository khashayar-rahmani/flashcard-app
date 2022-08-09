<?php

namespace Tests\Feature;

use App\Constants\FlashcardGameConstants;
use App\Models\Flashcard;
use App\Models\FlashcardPlayer;
use App\Models\Player;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FlashcardGameTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test value constants
     */
    protected const QUESTION = 'What is Laravel?';
    protected const CORRECT_ANSWER = 'A great PHP framework.';
    protected const INCORRECT_ANSWER = 'A great Java framework.';

    /**
     */
    public function test_create_a_flashcard()
    {
        $this->createAFlashcard();

        $this->assertDatabaseCount('flashcards', 1);
        $this->assertDatabaseCount('players', 1);
        $this->assertDatabaseCount('flashcard_player', 1);

        $this->assertDatabaseHas('flashcards', [
           'question'   => self::QUESTION,
           'answer'   => self::CORRECT_ANSWER,
        ]);

        /**
         * @var Flashcard $flashcard
         * @var Player $player
         */
        $flashcard = $this->getCreatedFlashcard();
        $player = $this->getCreatedPlayer();
        $flashcardPlayer = $this->getCreatedFlashcardPlayer($flashcard, $player)
        ;
        $this->assertEquals(FlashcardGameConstants::STATUS_NOT_ANSWERED, $flashcardPlayer->practice_status);
    }

    /**
     */
    public function test_answer_a_flashcard_incorrectly()
    {
        $this->createAFlashcard();

        /**
         * @var Flashcard $flashcard
         * @var Player $player
         */
        $flashcard = $this->getCreatedFlashcard();

        $this->answerAFlashcard($flashcard, self::INCORRECT_ANSWER);
        $player = $this->getCreatedPlayer();

        $flashcardPlayer = $this->getCreatedFlashcardPlayer($flashcard, $player);
        $this->assertEquals(FlashcardGameConstants::STATUS_INCORRECT, $flashcardPlayer->practice_status);
    }

    /**
     */
    public function test_answer_a_flashcard_correctly()
    {
        $this->createAFlashcard();

        /**
         * @var Flashcard $flashcard
         * @var Player $player
         */
        $flashcard = $this->getCreatedFlashcard();

        $this->answerAFlashcard($flashcard, self::CORRECT_ANSWER);
        $player = $this->getCreatedPlayer();

        $flashcardPlayer = $this->getCreatedFlashcardPlayer($flashcard, $player);

        $this->assertEquals(FlashcardGameConstants::STATUS_CORRECT, $flashcardPlayer->practice_status);
    }

    /**
     */
    public function test_reset_practice_progress() {

        $this->artisan('flashcard:interactive')
            ->expectsQuestion(FlashcardGameConstants::MESSAGE_ENTER_MENU_CHOICE, FlashcardGameConstants::MAIN_MENU_INDEX_CREATE_A_FLASHCARD)
            ->expectsQuestion(FlashcardGameConstants::MESSAGE_ENTER_FLASHCARD_QUESTION, self::QUESTION)
            ->expectsQuestion(FlashcardGameConstants::MESSAGE_ENTER_FLASHCARD_ANSWER, self::CORRECT_ANSWER)
            ->expectsQuestion(FlashcardGameConstants::MESSAGE_ENTER_MENU_CHOICE, FlashcardGameConstants::MAIN_MENU_INDEX_RESET)
            ->expectsQuestion(FlashcardGameConstants::MESSAGE_ENTER_MENU_CHOICE, FlashcardGameConstants::MAIN_MENU_INDEX_EXIT)
            ->assertExitCode(0);

        /**
         * @var Flashcard $flashcard
         * @var Player $player
         */
        $flashcard = $this->getCreatedFlashcard();
        $player = $this->getCreatedPlayer();

        $flashcardPlayer = $this->getCreatedFlashcardPlayer($flashcard, $player);

        $this->assertNull($flashcardPlayer);

    }

    /**
     */
    private function createAFlashcard() {
        $this->artisan('flashcard:interactive')
            ->expectsQuestion(FlashcardGameConstants::MESSAGE_ENTER_MENU_CHOICE, FlashcardGameConstants::MAIN_MENU_INDEX_CREATE_A_FLASHCARD)
            ->expectsQuestion(FlashcardGameConstants::MESSAGE_ENTER_FLASHCARD_QUESTION, self::QUESTION)
            ->expectsQuestion(FlashcardGameConstants::MESSAGE_ENTER_FLASHCARD_ANSWER, self::CORRECT_ANSWER)
            ->expectsQuestion(FlashcardGameConstants::MESSAGE_ENTER_MENU_CHOICE, FlashcardGameConstants::MAIN_MENU_INDEX_EXIT)
            ->assertExitCode(0);
    }

    /**
     * @param Flashcard $flashcard
     * @param string $answer
     */
    private function answerAFlashcard(Flashcard $flashcard, string $answer) {
        $this->artisan('flashcard:interactive')
            ->expectsQuestion(FlashcardGameConstants::MESSAGE_ENTER_MENU_CHOICE, FlashcardGameConstants::MAIN_MENU_INDEX_PRACTICE)
            ->expectsQuestion(FlashcardGameConstants::MESSAGE_ENTER_MENU_CHOICE, FlashcardGameConstants::PRACTICE_MENU_INDEX_PRACTICE_A_FLASHCARD)
            ->expectsQuestion(FlashcardGameConstants::MESSAGE_PICK_A_FLASHCARD_TO_PRACTICE, $flashcard->getId())
            ->expectsQuestion(FlashcardGameConstants::MESSAGE_ENTER_YOUR_ANSWER, $answer)
            ->expectsQuestion(FlashcardGameConstants::MESSAGE_ENTER_MENU_CHOICE, FlashcardGameConstants::PRACTICE_MENU_INDEX_BACK_TO_MAIN_MENU)
            ->expectsQuestion(FlashcardGameConstants::MESSAGE_ENTER_MENU_CHOICE, FlashcardGameConstants::MAIN_MENU_INDEX_EXIT)
            ->assertExitCode(0);
    }

    /**
     * @return object|null
     */
    private function getCreatedFlashcard(): object|null
    {
        return Flashcard::query()->orderByDesc('id')->first();
    }

    /**
     * @param Flashcard $flashcard
     * @param Player $player
     * @return object|null
     */
    private function getCreatedFlashcardPlayer(Flashcard $flashcard, Player $player): object|null
    {
        return FlashcardPlayer::query()
            ->where('flashcard_id', '=', $flashcard->getId())
            ->where('player_id', '=', $player->getId())
            ->first();
    }

    /**
     * @return Model|Builder|null
     */
    private function getCreatedPlayer(): Model|Builder|null
    {
        return Player::query()->orderByDesc('id')->first();
    }
}
