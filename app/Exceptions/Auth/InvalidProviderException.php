<?php

namespace App\Exceptions\Auth;

use Exception;

class InvalidProviderException extends Exception
{
    protected $message = 'Invalid OAuth provider.';
}
