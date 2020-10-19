<?php

declare(strict_types=1);

namespace Smsapi\Client\Infrastructure\Client\Decorator;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Smsapi\Client\SmsapiClient;

/**
 * @internal
 */
class GuzzleClientUserAgentHeaderDecorator implements ClientInterface
{
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $request = $this->addUserAgentHeader($request);

        return $this->client->sendRequest($request);
    }

    private function addUserAgentHeader(RequestInterface $request): RequestInterface
    {
        return $request->withHeader('User-Agent', $this->createUserAgent());
    }

    private function createUserAgent(): string
    {
        return sprintf(
            'smsapi/php-client:%s;guzzle:%s;php:%s',
            SmsapiClient::VERSION,
            GuzzleClientInterface::VERSION,
            PHP_VERSION
        );
    }
}