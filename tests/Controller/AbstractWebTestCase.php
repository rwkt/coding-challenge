<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use function json_encode;

abstract class AbstractWebTestCase extends WebTestCase
{
    protected function post(string $url, array $body, ?string $token = null): Response
    {
        $client = self::createClient();
        $client->request('POST', $url,
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
