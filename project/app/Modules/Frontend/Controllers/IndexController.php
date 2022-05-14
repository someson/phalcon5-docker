<?php

namespace App\Modules\Frontend\Controllers;

use Phalcon\Support\Version;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $css = $this->assets->collection('headerCss');
        $css->addCss('/assets/css/main.css');
        $css->addCss('/assets/libs/bootstrap/css/bootstrap.min.css');

        $this->flash->success('Success message');
        $this->flashSession->success('Success session message');

        $this->view->setVars([
            'frameworkVersion' => (new Version())->get(),
            'webServerVersion' => isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] . ' + ssl + http2' : '?',
            'phpVersion' => $_SERVER['PHP_VERSION'] ?? '?',
        ]);
    }
}
