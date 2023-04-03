<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Game extends Model
{
    use HasFactory;

    protected $table = 'games';

    protected $fillable = [
        'name',
        'description',
        'file',
        'category_id',
        'user_id'
    ];

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
