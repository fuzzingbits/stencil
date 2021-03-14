<?php

namespace FuzzingBits\Stencil\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    public function indexAction(): Response
    {
        return new Response('Index.');
    }

    public function helloAction(): Response
    {
        return new Response('Hello there.');
    }
}
