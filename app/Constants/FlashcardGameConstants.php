<?php

namespace App\Constants;

class FlashcardGameConstants
{
    /**
     * Status constants
     */
    public const STATUS_NOT_ANSWERED = 'Not answered';
    public const STATUS_CORRECT = 'Correct';
    public const STATUS_INCORRECT = 'Incorrect';

    /**
     * Main menu option constants.
     */
    public const MAIN_MENU_OPTION_CREATE_A_FLASHCARD = 'Create a flashcard';
    public const MAIN_MENU_OPTION_LIST_ALL_FLASHCARDS = 'List all flashcards';
    public const MAIN_MENU_OPTION_PRACTICE = 'Practice';
    public const MAIN_MENU_OPTION_STATS = 'Stats';
    public const MAIN_MENU_OPTION_RESET = 'Reset';
    public const MAIN_MENU_OPTION_EXIT = 'Exit';

    /**
     * Main menu index constants.
     */
    public const MAIN_MENU_INDEX_CREATE_A_FLASHCARD = 1;
    public const MAIN_MENU_INDEX_LIST_ALL_FLASHCARDS = 2;
    public const MAIN_MENU_INDEX_PRACTICE = 3;
    public const MAIN_MENU_INDEX_STATS = 4;
    public const MAIN_MENU_INDEX_RESET = 5;
    public const MAIN_MENU_INDEX_EXIT = 6;

    /**
     * Mappings for main menu options and their indexes.
     */
    public const MAIN_MENU_OPTION_INDEX_MAPPING = [
        self::MAIN_MENU_INDEX_CREATE_A_FLASHCARD     =>  self::MAIN_MENU_OPTION_CREATE_A_FLASHCARD,
        self::MAIN_MENU_INDEX_LIST_ALL_FLASHCARDS    =>  self::MAIN_MENU_OPTION_LIST_ALL_FLASHCARDS,
        self::MAIN_MENU_INDEX_PRACTICE               =>  self::MAIN_MENU_OPTION_PRACTICE,
        self::MAIN_MENU_INDEX_STATS                  =>  self::MAIN_MENU_OPTION_STATS,
        self::MAIN_MENU_INDEX_RESET                  =>  self::MAIN_MENU_OPTION_RESET,
        self::MAIN_MENU_INDEX_EXIT                   =>  self::MAIN_MENU_OPTION_EXIT,
    ];

    /**
     * Practice menu option constants.
     */
    public const PRACTICE_MENU_OPTION_PRACTICE_A_FLASHCARD = 'Practice a flashcard';
    public const PRACTICE_MENU_OPTION_BACK_TO_MAIN_MENU = 'Back to main menu';

    /**
     * Practice menu index constants.
     */
    public const PRACTICE_MENU_INDEX_PRACTICE_A_FLASHCARD = 1;
    public const PRACTICE_MENU_INDEX_BACK_TO_MAIN_MENU = 2;

    /**
     * Mappings for practice menu options and their indexes.
     */
    public const PRACTICE_MENU_OPTION_INDEX_MAPPING = [
        self::PRACTICE_MENU_INDEX_PRACTICE_A_FLASHCARD     =>  self::PRACTICE_MENU_OPTION_PRACTICE_A_FLASHCARD,
        self::PRACTICE_MENU_INDEX_BACK_TO_MAIN_MENU    =>  self::PRACTICE_MENU_OPTION_BACK_TO_MAIN_MENU,
    ];

    /**
     * Console message constants.
     */
    public const MESSAGE_ENTER_MENU_CHOICE = 'Please enter your choice';
    public const MESSAGE_ENTER_FLASHCARD_QUESTION = 'Please enter the question for the flashcard';
    public const MESSAGE_ENTER_FLASHCARD_ANSWER = 'Please enter the answer for the flashcard';
    public const MESSAGE_PICK_A_FLASHCARD_TO_PRACTICE = 'Please enter the id of the question that you want to practice';
    public const MESSAGE_FLASHCARD_ALREADY_ANSWERED = 'Oops, this flashcard has already a correct answer! please try another one!';
    public const MESSAGE_ENTER_YOUR_ANSWER = 'Please enter your answer';
    public const MESSAGE_CORRECT_ANSWER = 'Great job! your answer is correct';
    public const MESSAGE_INCORRECT_ANSWER = 'Oops, that is not the correct answer';
    public const MESSAGE_ALL_PRACTICE_PROGRESS_RESET = 'The practice progress is now reset and you can start again';

    /**
     * Console error message constants.
     */
    public const MESSAGE_NOT_A_VALID_CHOICE = 'Oops, not a valid choice! please try again!';
    public const MESSAGE_FLASHCARD_NOT_FOUND = 'Oops, flashcard not found! please try again!';

    /**
     * Console table constants.
     */
    public const ALL_FLASHCARD_TABLE_COLUMN_QUESTION = 'Question';
    public const ALL_FLASHCARD_TABLE_COLUMN_ANSWER = 'Answer';
    public const ALL_FLASHCARD_TABLE_HEADER = [
        self::ALL_FLASHCARD_TABLE_COLUMN_QUESTION,
        self::ALL_FLASHCARD_TABLE_COLUMN_ANSWER,
    ];

    public const STATS_TABLE_COLUMN_TOTAL_AMOUNT_OF_QUESTIONS = 'Total amount of questions';
    public const STATS_TABLE_COLUMN_QUESTIONS_PRACTICED_BY_YOU_PERCENTAGE = '% Questions practiced by you';
    public const STATS_TABLE_COLUMN_QUESTIONS_THAT_YOU_ANSWER_CORRECTLY = 'Questions that you answered correctly';
    public const STATS_TABLE_HEADER = [
        self::STATS_TABLE_COLUMN_TOTAL_AMOUNT_OF_QUESTIONS,
        self::STATS_TABLE_COLUMN_QUESTIONS_PRACTICED_BY_YOU_PERCENTAGE,
        self::STATS_TABLE_COLUMN_QUESTIONS_THAT_YOU_ANSWER_CORRECTLY,
    ];

    public const PRACTICE_FLASHCARD_TABLE_COLUMN_ID = 'Id';
    public const PRACTICE_FLASHCARD_TABLE_COLUMN_QUESTION = 'Question';
    public const PRACTICE_FLASHCARD_TABLE_COLUMN_STATUS = 'Status';
    public const PRACTICE_FLASHCARD_TABLE_HEADER = [
        self::PRACTICE_FLASHCARD_TABLE_COLUMN_ID,
        self::PRACTICE_FLASHCARD_TABLE_COLUMN_QUESTION,
        self::PRACTICE_FLASHCARD_TABLE_COLUMN_STATUS,
    ];
}
