<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\{BelongsToMany};
use Parental\HasParent;

class Admin extends User
{
    use HasParent;

    public function experiments(): BelongsToMany {
        return $this->belongsToMany(Experiment::class, 'experiment_user', 'user_id' , 'experiment_id');
    }
}
