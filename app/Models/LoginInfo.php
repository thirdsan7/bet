<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class LoginInfo extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    protected $table = 'getlogininfo';
    protected $primaryKey = 'getLoginInfoID';

    public $timestamps = false;

    public function gameSession()
    {
        return $this->belongsTo(GameSession::class, 'getLoginInfoID', 'getLoginInfoID');
    }
}
