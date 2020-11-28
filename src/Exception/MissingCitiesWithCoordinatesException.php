<?php

declare(strict_types=1);

namespace App\Exception;

use RuntimeException;

class MissingCitiesWithCoordinatesException extends RuntimeException
{
    public function __construct($message = '')
    {
        parent::__construct($message);
    }
}
