<?php

namespace FuzzingBits\Stencil\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    public function helloAction(): Response
    {
        return new Response('Hello there.');
    }

    public function indexAction(): Response
    {
        return new Response('Hello world.');
    }
}
