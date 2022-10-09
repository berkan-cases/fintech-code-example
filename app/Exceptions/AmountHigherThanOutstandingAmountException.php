<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;

class AmountHigherThanOutstandingAmountException extends Exception
{
    protected $message;

    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->message = $message;
    }

    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        report($this->message);

        return true;
    }
}
