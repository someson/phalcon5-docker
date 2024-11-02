<?php

namespace App\Modules\Api\Controllers\v1;

use App\Modules\Api\Controllers\ControllerBase;
use App\Shared\Notification;
use App\Version;

class IndexController extends ControllerBase
{
    public function getAction(): Notification
    {
        return Notification::success()->with([
            'module' => $this->router->getModuleName(),
            'api' => $this->router->getParams()['version'],
            'core' => (new Version)->getId(),
        ]);
    }
}
