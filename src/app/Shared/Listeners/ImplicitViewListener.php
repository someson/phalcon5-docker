<?php

namespace App\Shared\Listeners;

use Phalcon\Mvc\Application;
use Phalcon\Events\Event;

class ImplicitViewListener
{
    public function beforeStartModule(Event $event, Application $app, string $moduleName): bool
    {
        $module = $app->getModule($moduleName);
        if (isset($module['noView']) && $module['noView']) {
            $app->useImplicitView(false);
        }
        return ! $event->isStopped();
    }
}
