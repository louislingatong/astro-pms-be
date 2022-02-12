<?php

namespace App\Exceptions;

use Exception;

class ActivationTokenNotFoundException extends Exception
{
    /**
     * ActivationTokenNotFoundException constructor.
     * @param string $message
     */
    public function __construct($message = 'Invalid/Expired Activation Token.')
    {
        parent::__construct($message);
    }
}
