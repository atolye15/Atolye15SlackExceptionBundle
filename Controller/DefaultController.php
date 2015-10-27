<?php

namespace Atolye15\SlackExceptionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Atolye15SlackExceptionBundle:Default:index.html.twig', array('name' => $name));
    }
}
