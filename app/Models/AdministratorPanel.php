<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Auth;
use App\Models;
use App\Services\UserService;
use App\Http\Requests\Users\{UserCreateRequest, UserUpdateRequest};
use App\Http\Requests\Uploads\UploadUsersRequest;

class AdministratorPanel extends Model
{
    use HasFactory;

    
}
