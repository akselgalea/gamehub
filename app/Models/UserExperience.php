<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo};

class UserExperience extends Model
{
    use HasFactory;

    protected $table = 'user_experiences';

    protected $fillable = [
        'amount',
        'user_id',
        'game_instance_id'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function gameInstance(): BelongsTo {
        return $this->belongsTo(GameInstance::class);
    }
}
