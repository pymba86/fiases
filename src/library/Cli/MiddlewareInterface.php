<?php

namespace Library\Cli;

interface MiddlewareInterface
{
    /**
     * Calls the middleware
     *
     * @param Console $console
     */
    public function call(Console $console);

}
