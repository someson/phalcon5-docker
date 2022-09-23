<?php

namespace App\Modules\Frontend\Controllers;

use Phalcon\Support\Version;
use Phalcon\Tag;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        Tag::setTitle('phalcon 5.x index page');

        $css = $this->assets->collection('headerCss');
        $css->addCss('/assets/css/main.css');

        $this->flash->success('Success message');
        $this->flashSession->success('Success session message');

        $this->view->setVars([
            'frameworkVersion' => (new Version())->get(),
            'webServerVersion' => $_SERVER['SERVER_SOFTWARE'] ?? '?',
            'phpVersion' => $_SERVER['PHP_VERSION'] ?? '?',
        ]);
    }
}
