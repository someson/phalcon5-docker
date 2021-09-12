<?php

namespace App\Shared\Controllers;

use Phalcon\Http\Message\ResponseStatusCodeInterface as StatusCode;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Router\GroupInterface;

abstract class ControllerBase extends Controller
{
    protected function getModulePrefix(): ?string
    {
        /** @var GroupInterface $group */
        $group = $this->router->getMatchedRoute()->getGroup();
        return $group ? trim($group->getPrefix(), ' /') : null;
    }

    protected function redirect(mixed $to = null, bool $external = false, int $statusCode = StatusCode::STATUS_FOUND): bool
    {
        $parts = [];
        if ($prefix = $this->getModulePrefix()) {
            $parts[0] = trim($prefix, '/ ');
        }
        $target = explode('/', trim($to, '/ '));
        if (isset($parts[0]) && $target[0] === $parts[0]) {
            array_shift($target);
        }
        $parts = array_merge($parts, $target);
        $result = \count($parts) ? implode('/', $parts) : null;

        $this->response->redirect($result, $external, $statusCode);
        return false;
    }
}
