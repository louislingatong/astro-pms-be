<?php

namespace App\Exceptions;

use Exception;

class VesselMachineryNotFoundException extends Exception
{
    /** @var string */
    public $errorType = 'vessel_machinery_not_found';

    /**
     * VesselNotFoundException constructor.
     * @param string $message
     */
    public function __construct($message = 'Unable to retrieve vessel machinery.')
    {
        parent::__construct($message);
    }
}
