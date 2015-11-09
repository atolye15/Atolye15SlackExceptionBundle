<?php

namespace Atolye15\SlackExceptionBundle\Util;

class SlackRequest
{
  private static $baseUrl = "https://slack.com/api/chat.postMessage";
  private static function prepareArguments($arguments)
  {
    return http_build_query($arguments);
  }

  public static function makeRequest($arguments, $timeout = 3000)
  {
    $response = [
      "ok"    => false,
      "error" => "slack_request_error"
    ];

    try {
      $url = self::$baseUrl . "?" . self::prepareArguments($arguments);

      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET' );
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

      if ($timeout > 0) {
        curl_setopt($curl, CURLOPT_TIMEOUT, 3000);
      }

      $response = json_decode(curl_exec($curl), true);
      curl_close($curl);
      return $response;
    } catch (\Exception $e) {
      return $response;
    }
  }
}
