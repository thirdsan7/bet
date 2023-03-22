<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class GameLoginSession extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;
    
    protected $table = 'gameloginsession';
    protected $primaryKey = 'gameLoginInfoID';
    public $timestamps = false;

    public function loginInfo()
    {
        return $this->hasOne(LoginInfo::class, 'getLoginInfoID', 'getLoginInfoID');
    }
}
