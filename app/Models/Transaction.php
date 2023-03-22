<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Transaction extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    const CREATED_AT = 'timestampStart';
    const UPDATED_AT = 'timestampUpdated';

    protected $table = 'transactioncw';
    protected $primaryKey = 'transactionCWID';

    public $timestamps = true;
    protected $dateFormat = 'Y-m-d H:i:s.u';


    protected $fillable = [
        'sboClientID',
        'roundDetID',
        'gameID',
        'sessionID',
        'refNo',
        'stake',
        'totalwin',
        'event',
        'timestampEnd'
    ];
}
