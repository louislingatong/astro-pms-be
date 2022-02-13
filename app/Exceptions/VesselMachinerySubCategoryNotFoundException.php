<?php

namespace App\Exceptions;

use Exception;

class VesselMachinerySubCategoryNotFoundException extends Exception
{
    /** @var string */
    public $errorType = 'vessel_sub_category_not_found';

    /**
     * VesselNotFoundException constructor.
     * @param string $message
     */
    public function __construct($message = 'Unable to retrieve vessel sub category.')
    {
        parent::__construct($message);
    }
}
