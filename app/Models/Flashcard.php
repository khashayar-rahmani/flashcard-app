<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Flashcard
 * @package App\Models
 *
 * @property int $id
 * @property string $question
 * @property string $answer
 */
class Flashcard extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected array $fillable = [
        'question',
        'answer',
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getQuestion(): string
    {
        return $this->question;
    }

    /**
     * @return string
     */
    public function getAnswer(): string
    {
        return $this->answer;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->pivot->practice_status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->pivot->practice_status = $status;
    }

    /**
     * @return BelongsToMany
     */
    public function players(): BelongsToMany
    {
        return $this->belongsToMany(Player::class)->withPivot('practice_status');
    }
}
