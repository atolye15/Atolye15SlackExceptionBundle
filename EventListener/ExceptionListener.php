<?php

namespace Atolye15\SlackExceptionBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ExceptionListener
{
  private $container;

  public function __construct(ContainerInterface $container)
  {
    $this->container = $container;
  }

  public function onKernelException(GetResponseForExceptionEvent $event)
  {
    // Get slack post service
    $postService = $this
      ->container
      ->get("atolye15_slack_exception.post_service")
    ;

    // Send exception to Slack
    $postService->send($event->getException(), $event->getRequest());
  }
}
