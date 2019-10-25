<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use function json_encode;

abstract class AbstractWebTestCase extends WebTestCase
{
    protected function get(string $url, ?string $token = null): Response
    {
        return $this->request('GET', $url, [], $token);
    }

    protected function post(string $url, array $body, ?string $token = null): Response
    {
        return $this->request('POST', $url, $body, $token);
    }

    protected function put(string $url, array $body, ?string $token = null): Response
    {
        return $this->request('PUT', $url, $body, $token);
    }

    protected function delete(string $url, ?string $token = null): Response
    {
        return $this->request('DELETE', $url, [], $token);
    }

    private function request(string $method, string $url, array $body, ?string $token): Response
    {
        $client = self::createClient();
        $client->request($method, $url,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-AUTH-TOKEN' => $token,
            ],
            json_encode($body)
        );

        return $client->getResponse();
    }
}
