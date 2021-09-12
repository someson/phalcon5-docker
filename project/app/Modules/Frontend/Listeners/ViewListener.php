<?php

namespace App\Modules\Frontend\Listeners;

use App\View as ExtendedView;
use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use Phalcon\Flash\AbstractFlash;
use Phalcon\Mvc\View;

class ViewListener extends Injectable
{
    public function notFoundView(Event $event, ExtendedView $view): bool
    {
        /** @var AbstractFlash $flash */
        $flash = $this->getDI()->getShared('flash');
        $flash->setImplicitFlush(false);

        $level = $view->getCurrentRenderLevel();

        // do not search for controller layouts (they are not required)
        if ($level === View::LEVEL_LAYOUT) {
            return true;
        }

        switch ($level) {
            case View::LEVEL_ACTION_VIEW : // 1
                $message = 'Action view not found';
                break;
            case View::LEVEL_MAIN_LAYOUT : // 5
                $message = 'Main layout not found';
                break;
            default :
                $message = sprintf('View level %u not found', $level);
                break;
        }

        $content = $message . ' in: <strong>' . str_replace('\\', '/', $event->getData()) . '</strong>';
        $message = $flash->message('error', $content);
        $view->setContent($message . $view->getContent());

        return $event->isStopped();
    }
}
