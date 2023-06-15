<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Relations\{HasMany, HasManyThrough, BelongsToMany, BelongsTo};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Inertia\Inertia;
use App\Http\Requests\Experiments\Users\{UserAssociateRequest, UserDisassociateRequest};

class Experiment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'experiments';

    protected $fillable = [
        'name',
        'description',
        'status', // Detenido, Activo
        'time_limit', // In minutes
        'admin_id',
    ];

    public function creator(): BelongsTo {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function instances(): HasMany {
        return $this->hasMany(GameInstance::class);
    }

    public function games(): HasManyThrough {
        return $this->hasManyThrough(Game::class, GameInstance::class);
    }

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class, 'experiment_user' , 'experiment_id', 'user_id');
    }

    public function students(): BelongsToMany {
        return $this->belongsToMany(Student::class, 'experiment_user' , 'experiment_id', 'user_id');
    }

    public function entrypoints(): HasMany {
        return $this->hasMany(EntryPoint::class);
    }

    public function gameInstances(): HasMany {
        return $this->hasMany(GameInstance::class);
    }

    public function surveys(): HasMany {
        return $this->hasMany(Survey::class);
    }
}