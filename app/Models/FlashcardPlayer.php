<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashcardPlayer extends Model
{
    use HasFactory;

    protected $table = 'flashcard_player';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected array $fillable = [
        'player_id',
        'flashcard_id',
        'player_answer',
        'practice_status',
    ];
}
