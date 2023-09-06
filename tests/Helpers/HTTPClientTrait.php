<?php

namespace Tests\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

trait HTTPClientTrait
{
  /**
   * @throws GuzzleException
   */
  public static function doPost(string $url, array $args, array $options = []): ResponseInterface
  {
    $client = new Client();
    $response = $client->request('POST', $url, $args, $options);

    return $response;
  }
}
