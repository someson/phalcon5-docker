<?php

namespace App\Modules\Api\Exceptions;

use App\Shared\Http\Status;
use Phalcon\Http\Response\Exception;

class HttpClientException extends Exception
{
    public function __construct(int $httpCode, \Throwable $previous = null)
    {
        $httpStatus = Status::tryFrom($httpCode);
        parent::__construct($httpStatus->message(), $httpCode, $previous);
    }
}
