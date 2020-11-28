<?php

declare(strict_types=1);

namespace App\Exception;

use RuntimeException;

class MissingCityWithWeatherException extends RuntimeException
{
    public function __construct($message = '')
    {
        parent::__construct($message);
    }
}
