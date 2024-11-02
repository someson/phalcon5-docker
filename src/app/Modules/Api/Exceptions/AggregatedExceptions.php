<?php

namespace App\Modules\Api\Exceptions;

use App\Shared\Http\Status;
use Phalcon\Http\Response\Exception;

class AggregatedExceptions extends Exception implements CollectableInterface
{
    public function __construct(private readonly array $messages, ?string $ownMessage = null)
    {
        parent::__construct($ownMessage ?? '', Status::PRECONDITION_FAILED);
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
