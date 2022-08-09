<?php

namespace App\Services;

use App\Constants\FlashcardGameConstants;
use App\Helper\StringHelper;
use App\Models\Flashcard;
use App\Models\FlashcardPlayer;
use App\Models\Player;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FlashcardGameService
{
    /**
     * @return Player
     */
    public static function createPlayer(): Player
    {
        $player = new Player();
        $player->save();
        return $player;
    }

    /**
     * @return array
     */
    public static function getMainMenuOptions(): array
    {
        $menuOptions = [];

        foreach (FlashcardGameConstants::MAIN_MENU_OPTION_INDEX_MAPPING as $key => $value) {
            $menuOptions[] = StringHelper::formatMenuOption($key, $value);
        }

        return $menuOptions;
    }

    /**
     * @return array
     */
    public static function getPracticeMenuOptions(): array
    {
        $menuOptions = [];

        foreach (FlashcardGameConstants::PRACTICE_MENU_OPTION_INDEX_MAPPING as $key => $value) {
            $menuOptions[] = StringHelper::formatMenuOption($key, $value);
        }

        return $menuOptions;
    }

    /**
     * @param int $playerChoice
     * @return bool
     */
    public static function isPlayerPlaying(int $playerChoice): bool {
        if (FlashcardGameConstants::MAIN_MENU_INDEX_EXIT !== $playerChoice) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param Player $player
     * @param string $question
     * @param string $answer
     */
    public static function createFlashCard(Player $player, string $question, string $answer): void
    {
        DB::transaction(function () use($player, $question, $answer){
            /**
             * @var Flashcard $flashcard
             */
            $flashcard = Flashcard::query()->firstOrCreate([
                'question'  => $question,
                'answer'  => $answer,
            ]);
            FlashcardPlayer::query()->firstOrCreate(
                [
                    'flashcard_id'  => $flashcard->getId(),
                    'player_id'     => $player->getId(),
                ]
            );
        });
    }

    /**
     * @return array
     */
    public static function getAllFlashcards(): array
    {
        return Flashcard::all()->sortBy('id')->map(function($flashcard) {
            return [
                'question'  => $flashcard->question,
                'answer'    => $flashcard->answer,
            ];
        })->toArray();
    }

    /**
     * @param Player $player
     * @return array
     */
    public static function getFlashcardsToPractice(Player $player): array
    {
        return $player->getFlashcardsWithStatus()->sortBy('id')->map(function($flashcard) {
            return [
                'id'        => $flashcard->id,
                'question'  => $flashcard->question,
                'status'    => $flashcard->status,
            ];
        })->toArray();
    }

    /**
     * @param array $flashcards
     * @return float
     */
    public static function determineCorrectlyAnsweredFlashcardsPercentage(array $flashcards): float
    {
        $collectionFlashcards = collect($flashcards);
        $countCorrectlyAnsweredFlashcards = $collectionFlashcards
            ->where('status', '=', FlashcardGameConstants::STATUS_CORRECT)->count();

        return self::calculatePercentage($collectionFlashcards->count(), $countCorrectlyAnsweredFlashcards);
    }

    /**
     * @param mixed $flashcardId
     * @return Builder|Model|null
     */
    public static function getFlashcardOrNull(mixed $flashcardId): Builder|Model|null
    {
        return Flashcard::query()->find($flashcardId);
    }

    /**
     * @param Player $player
     * @param Flashcard $flashcard
     * @return bool
     */
    public static function canPracticeFlashcard(Player $player, Flashcard $flashcard): bool
    {
        $playerFlashcard = $player->flashcards()->find($flashcard->getId());

        if (is_null($playerFlashcard)) { // Player has not practiced this flashcard yet
            return true;
        } elseif ($playerFlashcard->pivot->practice_status === FlashcardGameConstants::STATUS_CORRECT){
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param string $answer
     * @param Flashcard $flashcard
     * @return string
     */
    public static function determinStatus(string $answer, Flashcard $flashcard): string
    {
        if ($answer === $flashcard->getAnswer()) {
            return FlashcardGameConstants::STATUS_CORRECT;
        } else {
            return FlashcardGameConstants::STATUS_INCORRECT;
        }
    }

    /**
     * @param Player $player
     * @param Flashcard $flashcard
     * @param string $answer
     * @param string $status
     */
    public static function storeAnswer(Player $player, Flashcard $flashcard, string $answer, string $status): void
    {
        FlashcardPlayer::query()->updateOrCreate([
            'player_id' => $player->getId(),
            'flashcard_id' => $flashcard->getId(),
        ], [
            'player_id' => $player->getId(),
            'flashcard_id' => $flashcard->getId(),
            'player_answer' => $answer,
            'practice_status' => $status,
        ]);
    }

    /**
     * @param int $playerChoice
     * @return bool
     */
    public static function isPlayerPracticing(int $playerChoice): bool {
        if (FlashcardGameConstants::PRACTICE_MENU_INDEX_BACK_TO_MAIN_MENU !== $playerChoice) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return int
     */
    public static function getTotalAmountOfQuestions(): int
    {
        return Flashcard::all()->count();
    }

    /**
     * @param Player $player
     * @return float
     */
    public static function getPercentageOfAllQuestionsHavingAnswer(Player $player): float
    {
        $allFlashcards = $player->getFlashcardsWithStatus();
        $answeredFlashcards = $allFlashcards->where('status', '<>', FlashcardGameConstants::STATUS_NOT_ANSWERED)->count();

        return self::calculatePercentage($allFlashcards->count(), $answeredFlashcards);
    }

    /**
     * @param Player $player
     * @return float
     */
    public static function getPercentageOfAllQuestionsHavingCorrectAnswer(Player $player): float
    {
        return self::determineCorrectlyAnsweredFlashcardsPercentage(self::getFormattedFlashcardTable($player));
    }

    /**
     * @param Player $player
     */
    public static function resetPracticeProgress(Player $player) {
        $player->flashcards()->detach();
    }

    /**
     * @param int $countAllFlashcards
     * @param int $countCorrectlyAnsweredFlashcardsByPlayer
     * @return float
     */
    private static function calculatePercentage(int $countAllFlashcards, int $countCorrectlyAnsweredFlashcardsByPlayer): float
    {
        $percentage = ($countCorrectlyAnsweredFlashcardsByPlayer / $countAllFlashcards) * 100;
        return number_format($percentage, 2);
    }

    /**
     * @param Player $player
     * @return array
     */
    private static function getFormattedFlashcardTable(Player $player): array
    {
        return $player->getFlashcardsWithStatus()->map(function($flashcard) {
            return [
                'id'    => $flashcard->id,
                'question'    => $flashcard->question,
                'status'    => $flashcard->status,
            ];
        })->toArray();
    }
}
