<?php

namespace App\Exceptions;

use Exception;

class VesselNotFoundException extends Exception
{
    /** @var string */
    public $errorType = 'vessel_not_found';

    /**
     * VesselNotFoundException constructor.
     * @param string $message
     */
    public function __construct($message = 'Unable to retrieve vessel.')
    {
        parent::__construct($message);
    }
}
