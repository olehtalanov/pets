<?php

namespace App\Exceptions\Common;

use Exception;

class ResourceNotSetException extends Exception
{
    protected $message = 'Paginating resource not set.';
}
