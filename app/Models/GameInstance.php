<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{belongsToMany, BelongsTo, HasMany};
use Illuminate\Database\Eloquent\SoftDeletes;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Spatie\Sluggable\{HasSlug, SlugOptions};

class GameInstance extends Model
{
    use HasFactory, HasSlug;

    protected $table = 'game_instances';
    protected $fillable = [
        'name',
        'slug',
        'description',
        'enable_rewards',
        'enable_badges',
        'enable_performance_chart',
        'enable_leaderboard',
        'game_id',
        'experiment_id',
    ];

    protected $cast = [
        'enable_rewards' => 'boolean',
        'enable_badges' => 'boolean',
        'enable_performance_chart' => 'boolean',
        'enable_leaderboard' => 'boolean'
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function game(): BelongsTo {
        return $this->belongsTo(Game::class);
    }

    public function experiment(): BelongsTo {
        return $this->belongsTo(Experiment::class);
    }

    public function gameInstanceParameters(): HasMany {
        return $this->hasMany(GameInstanceParameter::class);
    }

    public function parameters(): BelongsToMany {
        return $this->belongsToMany(Parameter::class, 'game_instance_parameters', 'game_instance_id', 'parameter_id');
    }

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class, 'experiment_user' , 'user_id', 'game_instance_id');
    }

    public function scopeFindByExperiment(Builder $query, $experiment): void {
        $query->where('game_instances.experiment_id', $experiment)->orderByDesc('created_at');
    }

    public function scopeOrderByUserCount(Builder $query, $dir): void {
        $query->withCount('users')->orderBy('users_count', $dir);
    }

    public function getInstanceWithLeastUsers($experiment) {
        return GameInstance::findByExperiment($experiment)->orderByUserCount('asc')->first();
    }

    public function getInstanceWithMostUsers($experiment) {
        return GameInstance::findByExperiment($experiment)->orderByUserCount('desc')->first();
    }

    public function findBySlug($slug) {
        return GameInstance::firstWhere('slug', $slug);
    }

    public function updateGamification($req, $id) {
        $validated = $req->validated();
        try {
            $game_instance = GameInstance::findOrFail($id);
            $game_instance->update($validated);
            
            return ['status' => 200, 'message' => 'Gamificacion actualizada con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }
}
