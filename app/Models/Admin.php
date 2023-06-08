<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\{HasMany};
use Parental\HasParent;

class Admin extends User
{
    use HasParent;

    public function experiments(): HasMany {
        return $this->hasMany(Experiment::class, 'admin_id');
    }
}
