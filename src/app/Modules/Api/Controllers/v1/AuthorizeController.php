<?php

namespace App\Modules\Api\Controllers\v1;

use App\Modules\Api\Attributes\ApiAuth;
use App\Modules\Api\Controllers\ControllerBase;
use App\Shared\Notification;
use Phalcon\Http\Message\RequestMethodInterface as Http;

class AuthorizeController extends ControllerBase
{
    public function postAction(): void
    {
        // business logic
        // ...

        $this->dispatcher->forward([
            'controller' => 'authorize',
            'action' => strtolower(Http::METHOD_GET),
        ]);
    }

    #[ApiAuth(type: 'bearer')]
    public function getAction(): Notification
    {
        return Notification::success()->with([
            'authorized' => true,
        ]);
    }
}
