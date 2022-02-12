<?php

namespace App\Exceptions;

use Exception;

class AuthModelNotSetException extends Exception
{
    /** @var string */
    public $errorType = 'missing_auth_model';

    /**
     * AuthModelNotSetException constructor.
     * @param string $message
     */
    public function __construct($message = 'Auth Model not set.')
    {
        parent::__construct($message);
    }
}
