<?php


namespace App\Services;


use App\Constants\FlashcardGameConstants;

class ValidationService
{
    /**
     * @param $menuOption
     * @return bool
     */
    public static function isValidMainMenuOption($menuOption): bool
    {
        if (array_key_exists($menuOption, FlashcardGameConstants::MAIN_MENU_OPTION_INDEX_MAPPING)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $menuOption
     * @return bool
     */
    public static function isValidPracticeMenuOption($menuOption): bool
    {
        if (array_key_exists($menuOption, FlashcardGameConstants::PRACTICE_MENU_OPTION_INDEX_MAPPING)) {
            return true;
        } else {
            return false;
        }
    }
}
