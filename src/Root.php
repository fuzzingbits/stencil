<?php

namespace FuzzingBits\Stencil;

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

class Root
{
    public static function handle(string $root): void
    {
        (new Dotenv())->bootEnv($root . '/.env');

        if ($_SERVER['APP_DEBUG']) {
            umask(0000);

            Debug::enable();
        }

        $kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
        $kernel->setProjectDir($root);
        $request = Request::createFromGlobals();
        $response = $kernel->handle($request);
        $response->send();
        $kernel->terminate($request, $response);
    }
}
