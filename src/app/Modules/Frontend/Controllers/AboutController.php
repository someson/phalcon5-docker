<?php

namespace App\Modules\Frontend\Controllers;

class AboutController extends ControllerBase
{
    public function indexAction(): void
    {
        $css = $this->assets->collection('headerCss');
        $css->addCss('/assets/css/main.css');
    }
}
