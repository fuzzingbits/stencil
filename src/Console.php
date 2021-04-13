<?php

namespace FuzzingBits\Stencil;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;

set_time_limit(0);

class Console
{
    public static function execute(string $root): void
    {
        $input = new ArgvInput();
        if (null !== $env = $input->getParameterOption(['--env', '-e'], null, true)) {
            putenv('APP_ENV=' . $_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = $env);
        }

        (new Dotenv())->bootEnv($root . '/.env');

        if ($_SERVER['APP_DEBUG']) {
            umask(0000);

            if (class_exists(Debug::class)) {
                Debug::enable();
            }
        }

        $kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
        $kernel->setProjectDir($root);
        $application = new Application($kernel);
        $application->run($input);
    }
}
