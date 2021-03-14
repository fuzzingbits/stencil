<?php

namespace FuzzingBits\Stencil;

use FuzzingBits\Stencil\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

class Root
{
    public static function handle(string $root) {
        (new Dotenv())->bootEnv($root.'/.env');

        if ($_SERVER['APP_DEBUG']) {
            umask(0000);

            Debug::enable();
        }

        $kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
        $request = Request::createFromGlobals();
        $response = $kernel->handle($request);
        $response->send();
        $kernel->terminate($request, $response);
    }
}
