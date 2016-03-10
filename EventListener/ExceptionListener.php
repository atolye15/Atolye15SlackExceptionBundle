<?php

namespace Atolye15\SlackExceptionBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionListener
{
  private $container;

  public function __construct(ContainerInterface $container)
  {
    $this->container = $container;
  }

  public function onKernelException(GetResponseForExceptionEvent $event)
  {
    $exception = $event->getException();

    if ($exception instanceof NotFoundHttpException) {
      return false;
    }

    // Get slack post service
    $postService = $this
      ->container
      ->get("atolye15_slack_exception.post_service")
    ;

    // Send exception to Slack
    $postService->send($exception, $event->getRequest());
  }
}
