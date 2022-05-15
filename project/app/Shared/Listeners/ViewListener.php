<?php

namespace App\Shared\Listeners;

use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use Phalcon\Flash\Direct;
use Phalcon\Mvc\View;

class ViewListener extends Injectable
{
    public function notFoundView(Event $event, View $view): bool
    {
        /** @var Direct $flash */
        $flash = $this->getDI()->getShared('flash');
        $flash->setImplicitFlush(false);

        $level = $view->getCurrentRenderLevel();

        // do not search for controller layouts (not required)
        if ($level === View::LEVEL_LAYOUT) {
            return true;
        }
        $message = match ($level) {
            View::LEVEL_ACTION_VIEW => 'Action view not found',
            View::LEVEL_MAIN_LAYOUT => 'Main layout not found',
            default => sprintf('View level %u not found', $level),
        };
        $content = $message . ' in: <strong>' . str_replace('\\', '/', $event->getData()) . '</strong>';
        $message = $flash->message('error', $content);
        $view->setContent($message . $view->getContent());

        return $event->isStopped();
    }
}
