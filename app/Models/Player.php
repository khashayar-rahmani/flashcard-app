<?php

namespace App\Models;

use App\Constants\FlashcardGameConstants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class Player
 * @package App\Models
 *
 * @property int $id
 */
class Player extends Model
{
    use HasFactory;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return BelongsToMany
     */
    public function flashcards(): BelongsToMany
    {
        return $this->belongsToMany(Flashcard::class)->withPivot('practice_status');
    }

    /**
     * @return Collection
     */
    public function getCorrectlyAnsweredFlashcards(): Collection
    {
        return $this->flashcards()
            ->where('practice_status', '=', FlashcardGameConstants::STATUS_CORRECT)
            ->get();
    }

    /**
     * @return Collection
     */
    public function getFlashcardsWithStatus(): Collection
    {
        $statusNotAnswered = FlashcardGameConstants::STATUS_NOT_ANSWERED;

        return DB::table('flashcards')
            ->selectRaw(
                "
                    flashcards.id as id,
                    flashcards.question as question,
                    coalesce(flashcard_player.practice_status, ?) as status
                 ",
                [$statusNotAnswered]
            )
            ->leftJoin('flashcard_player', function ($join) {
                $join->on('flashcard_player.flashcard_id', '=', 'flashcards.id')
                    ->where('flashcard_player.player_id', '=', $this->getId());
            })
            ->get();
    }
}
