<?php

namespace Atolye15\SlackExceptionBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Atolye15\SlackExceptionBundle\Util\SlackRequest;
use Atolye15\SlackExceptionBundle\Exception\SlackResponseException;

class PostService
{
  private $container;
  private $user;

  private $parameters;

  public function __construct(ContainerInterface $container, TokenStorage $tokenStorage)
  {
    $this->container = $container;
    $this->user = $tokenStorage->getToken() !== null ? $tokenStorage->getToken()->getUser() : null;
  }

  private function getParameter($parameter)
  {
    if (!$this->parameters) {
      $this->parameters = $this->container->getParameter("atolye15_slack_exception");
    }

    return $this->parameters[$parameter];
  }

  private function parseChannel($channel) {
    return preg_match("/^\#/", $channel) ? $channel : "#" . $channel;
  }

  private function buildMessage(\Exception $exception, Request $request)
  {
    $projectName = $this->getParameter('project');
    $now         = new \DateTime();
    $date        = $now->format("Y/m/d H:i:s");

    $message  = sprintf("Oops! Something went wrong on *%s* at _%s_", $projectName, $date);
    $message .= sprintf("\n*URL:* %s", $request->getUri());
    if (null !== $this->user && method_exists($this->user, "getUsername")) {
      $message .= sprintf("\n*User:* %s", $this->user->getUsername());
    }
    $message .= sprintf("\n*File:* `%s`", $exception->getFile());
    $message .= sprintf("\n*Line:* %s", $exception->getLine());
    $message .= sprintf("\n```%s```", $exception->getMessage());

    return $message;
  }

  public function send(\Exception $exception, Request $request)
  {
    $environment = $this->container->get('kernel')->getEnvironment();
    if ($this->getParameter("environment") == "prod" && $environment != "prod") {
      return false;
    }

    $message  = $this->buildMessage($exception, $request);
    $channel  = $this->parseChannel($this->getParameter("channel"));

    $response = SlackRequest::makeRequest([
      "token"      => $this->getParameter("token"),
      "channel"    => $channel,
      "text"       => $message,
      "username"   => $this->getParameter("username"),
      "parse"      => "full",
      "link_names" => 1
    ], $this->getParameter("request_timeout"));

    if (!$response["ok"] && $this->getParameter("throw_exception")) {
      throw new SlackResponseException("Slack message could not be send. Error: " . $response["error"]);
    }
  }
}
