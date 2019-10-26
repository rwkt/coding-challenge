<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\DataFixtures\CategoryFixtures;
use App\DataFixtures\ProductFixtures;
use App\DataFixtures\UserFixtures;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use function json_encode;

abstract class AbstractWebTestCase extends WebTestCase
{
    use FixturesTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([
            UserFixtures::class,
            CategoryFixtures::class,
            ProductFixtures::class,
        ]);
    }

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
        $headers = [
            'CONTENT_TYPE' => 'application/json',
        ];

        if ($token) {
            $headers['HTTP_X-AUTH-TOKEN'] = $token;
        }

        $client = self::createClient();
        $client->request($method, $url,
            [],
            [],
            $headers,
            json_encode($body, JSON_THROW_ON_ERROR, 512)
        );

        return $client->getResponse();
    }
}
