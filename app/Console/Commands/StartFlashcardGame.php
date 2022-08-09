<?php

namespace App\Console\Commands;

use App\Constants\FlashcardGameConstants;
use App\Helper\StringHelper;
use App\Models\Flashcard;
use App\Models\Player;
use App\Services\FlashcardGameService;
use App\Services\ValidationService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\Table;

class StartFlashcardGame extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashcard:interactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Table Text constants.
     */
    protected const TEXT_FOOTER = 'Completed';

    protected Player $player;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->player = FlashcardGameService::createPlayer();
        do {
            $playerChoice = $this->getPlayerChoiceMainMenu();
            $this->handlePlayerChoiceMainMenu($playerChoice);
        } while (FlashcardGameService::isPlayerPlaying($playerChoice));

        return 0;
    }

    /**
     * @return int
     */
    private function getPlayerChoiceMainMenu(): int
    {
        $this->showMainMenu();
        $playerChoice = $this->ask(FlashcardGameConstants::MESSAGE_ENTER_MENU_CHOICE);

        if (ValidationService::isValidMainMenuOption($playerChoice)) {
            return $playerChoice;
        } else {
            $this->error(FlashcardGameConstants::MESSAGE_NOT_A_VALID_CHOICE);
            return $this->getPlayerChoiceMainMenu();
        }
    }

    /**
    */
    private function showMainMenu(): void
    {
        $menuOptions = FlashcardGameService::getMainMenuOptions();
        $this->newLine();

        foreach ($menuOptions as $menuOption) {
            $this->line($menuOption);
        }
    }

    /**
     * @param int $playerChoice
     */
    private function handlePlayerChoiceMainMenu(int $playerChoice): void
    {
        switch ($playerChoice) {
            case FlashcardGameConstants::MAIN_MENU_INDEX_CREATE_A_FLASHCARD:
                $this->createFlashCard();
                break;
            case FlashcardGameConstants::MAIN_MENU_INDEX_LIST_ALL_FLASHCARDS:
                $this->listAllFlashCards();
                break;
            case FlashcardGameConstants::MAIN_MENU_INDEX_PRACTICE:
                $this->practice();
                break;
            case FlashcardGameConstants::MAIN_MENU_INDEX_STATS:
                $this->showTheStats();
                break;
            case FlashcardGameConstants::MAIN_MENU_INDEX_RESET:
                $this->resetTheGame();
                break;
        }
    }

    /**
     */
    private function createFlashCard() {
        $flashcardQuestion = $this->ask(FlashcardGameConstants::MESSAGE_ENTER_FLASHCARD_QUESTION);
        $flashcardAnswer = $this->ask(FlashcardGameConstants::MESSAGE_ENTER_FLASHCARD_ANSWER);

        FlashcardGameService::createFlashCard($this->player, $flashcardQuestion, $flashcardAnswer);
    }

    /**
     */
    private function listAllFlashCards() {
        $allFlashcards = FlashcardGameService::getAllFlashcards();

        $this->table(
            FlashcardGameConstants::ALL_FLASHCARD_TABLE_HEADER,
            $allFlashcards
        );
    }

    /**
     */
    private function practice() {
        do {
            $playerChoice = $this->getPlayerChoicePracticeMenu();
            $this->handlePlayerChoicePracticeMenu($playerChoice);
        } while (FlashcardGameService::isPlayerPracticing($playerChoice));
    }

    /**
     */
    private function showTheStats() {
        $percentageOfAllQuestionsHavingAnswer = FlashcardGameService::getPercentageOfAllQuestionsHavingAnswer($this->player);
        $percentageOfAllQuestionsHavingCorrectAnswer = FlashcardGameService::getPercentageOfAllQuestionsHavingCorrectAnswer($this->player);
        $this->table(
            FlashcardGameConstants::STATS_TABLE_HEADER,
            [
                [
                    FlashcardGameService::getTotalAmountOfQuestions(),
                    StringHelper::formatNumberPercentage($percentageOfAllQuestionsHavingAnswer),
                    StringHelper::formatNumberPercentage($percentageOfAllQuestionsHavingCorrectAnswer),
                ]
            ]
        );
    }

    /**
     */
    private function resetTheGame() {
        FlashcardGameService::resetPracticeProgress($this->player);
        $this->info(FlashcardGameConstants::MESSAGE_ALL_PRACTICE_PROGRESS_RESET);
    }

    /**
     */
    private function showFlashcardsTable() {
        $allFlashcards = FlashcardGameService::getFlashcardsToPractice($this->player);
        $correctlyAnsweredFlashcardsPercentage = FlashcardGameService::determineCorrectlyAnsweredFlashcardsPercentage($allFlashcards);

        $table = new Table($this->output);
        $table->setStyle('box');
        $table
            ->setHeaders(FlashcardGameConstants::PRACTICE_FLASHCARD_TABLE_HEADER)
            ->setRows($allFlashcards)
            ->setFooterTitle(StringHelper::formatMenuFooter(self::TEXT_FOOTER, $correctlyAnsweredFlashcardsPercentage));
        $table->render();
    }

    /**
     */
    private function showPracticeMenu() {

        $menuOptions = FlashcardGameService::getPracticeMenuOptions();
        $this->newLine();

        foreach ($menuOptions as $menuOption) {
            $this->line($menuOption);
        }
    }

    /**
     * @return int
     */
    private function getPlayerChoicePracticeMenu(): int
    {
        $this->showFlashcardsTable();
        $this->showPracticeMenu();
        $playerChoice = $this->ask(FlashcardGameConstants::MESSAGE_ENTER_MENU_CHOICE);

        if (ValidationService::isValidPracticeMenuOption($playerChoice)) {
            return $playerChoice;
        } else {
            $this->error(FlashcardGameConstants::MESSAGE_NOT_A_VALID_CHOICE);
            return $this->getPlayerChoicePracticeMenu();
        }
    }

    /**
     * @param int $playerChoice
     */
    private function handlePlayerChoicePracticeMenu(int $playerChoice): void
    {
        switch ($playerChoice) {
            case FlashcardGameConstants::PRACTICE_MENU_INDEX_PRACTICE_A_FLASHCARD:
                $this->practiceFlashcard();
                break;
        }
    }

    /**
     */
    private function practiceFlashcard() {
        $flashcardId = $this->ask(FlashcardGameConstants::MESSAGE_PICK_A_FLASHCARD_TO_PRACTICE);
        $flashcard = FlashcardGameService::getFlashcardOrNull($flashcardId);

        /**
         * @var Flashcard $flashcard
         */
        if (is_null($flashcard)) {
            $this->error(FlashcardGameConstants::MESSAGE_FLASHCARD_NOT_FOUND);
        } elseif (FlashcardGameService::canPracticeFlashcard($this->player, $flashcard)) {
            $playerAnswer = $this->ask(FlashcardGameConstants::MESSAGE_ENTER_YOUR_ANSWER);
            $this->handlePlayerAnswer($playerAnswer, $flashcard);
        } else {
            $this->warn(FlashcardGameConstants::MESSAGE_FLASHCARD_ALREADY_ANSWERED);
        }
    }

    /**
     * @param string $answer
     * @param Flashcard $flashcard
     */
    private function handlePlayerAnswer(string $answer, Flashcard $flashcard) {
        $status = FlashcardGameService::determinStatus($answer, $flashcard);

        if ($status === FlashcardGameConstants::STATUS_CORRECT) {
            $this->info(FlashcardGameConstants::MESSAGE_CORRECT_ANSWER);
        } else {
            $this->warn(FlashcardGameConstants::MESSAGE_INCORRECT_ANSWER);
        }

        FlashcardGameService::storeAnswer($this->player, $flashcard, $answer, $status);
    }
}
