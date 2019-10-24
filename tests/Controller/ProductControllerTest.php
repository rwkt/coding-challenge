<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\ProductController;
use Generator;
use Symfony\Component\HttpFoundation\Response;
use function array_merge;

class ProductControllerTest extends AbstractWebTestCase
{
    /**
     * @see ProductController::list()
     */
    public function testList(): void
    {
        $client = self::createClient();
        $client->request('GET', '/product');
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());
    }

    /**
     * @see ProductController::create()
     */
    public function testCreateWithoutValidToken(): void
    {
        $response = $this->post('/product', ['name' => 'Test']);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        $response = $this->post('/product', ['name' => 'Test'], 'Invalid token');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    /**
     * @see ProductController::create()
     *
     * @dataProvider provideInvalidData
     */
    public function testCreateWithInvalidData($data): void
    {
        $response = $this->post('/product', $data, 'foo_token');
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function provideInvalidData(): Generator
    {
        $validData = $this->getValidData();

        yield ['data' => array_merge($validData, ['name' => ''])];
        yield ['data' => array_merge($validData, ['price' => ''])];
        yield ['data' => array_merge($validData, ['category' => '10001'])];
    }

    private function getValidData(): array
    {
        return [
            'name' => 'Valid name',
            'price' => 42.42,
            'sku' => 'sku12',
            'quantity' => 100,
            'category' => 1,
        ];
    }
}
