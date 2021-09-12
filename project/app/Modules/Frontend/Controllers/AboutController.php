<?php

namespace App\Modules\Frontend\Controllers;

class AboutController extends ControllerBase
{
    public function indexAction()
    {
        $css = $this->assets->collection('headerCss');
        $css->addCss('/assets/css/main.css');
        $css->addCss('/assets/libs/bootstrap/css/bootstrap.min.css');
    }
}
