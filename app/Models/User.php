<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Requests\Users\{UserCreateRequest};
use Parental\HasChildren;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasChildren;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'grade_id'
    ];

    protected $childTypes = [
        'admin' => Admin::class,
        'student' => Student::class,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function games(): HasMany {
        return $this->hasMany(User::class);
    }

    

    public function isAdmin() {
        return $this->type == 'admin';
    }

    public function isStudent() {
        return $this->type == 'student';
    }

    public function store(UserCreateRequest $req) {
        
        $validated = $req->validated();

        try {
            $validated['password'] = Hash::make($validated['password']); // se cifra la contrasenia
            
            $user = User::create($validated);
            
            $rememberToken = Str::random(60); // se le crea un token aleatorio para recordar al usuario
            $user->remember_token = $rememberToken;// se le asigna al usuario
            $user->save();
            
            return ['status' => 200, 'message' => 'Experimento creado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }
}
