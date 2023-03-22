<?php
namespace App\Exceptions\General;

use Illuminate\Support\Facades\DB;

class InvalidInputException extends \Exception
{
    protected $code = 1;
}