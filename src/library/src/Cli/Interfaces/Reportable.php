<?php

namespace Library\Cli\Interfaces;

interface Reportable
{
    /**
     * @param array $attr
     * @return mixed
     */
    public function report(array $attr);

    /**
     * FQDN (namespaced classname as an ID)
     * @return string
     */
    public function getName(): string;
}
