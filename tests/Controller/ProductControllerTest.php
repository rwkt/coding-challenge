<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\ProductController;
use Generator;
use function array_merge;
use function json_decode;

class ProductControllerTest extends AbstractWebTestCase
{
    /**
     * @see ProductController::list()
     */
    public function testList(): void
    {
        $response = $this->get('/product');
        $this->assertTrue($response->isOk());
    }

    /**
     * @see ProductController::create()
     *
     * Resource is forbidden if there is no token or user cannot be found
     */
    public function testCreateWithoutValidToken(): void
    {
        $response = $this->post('/product', ['name' => 'Test']);
        $this->assertEquals(401, $response->getStatusCode());
        $response = $this->post('/product', ['name' => 'Test'], 'Invalid token');
        $this->assertEquals(403, $response->getStatusCode());
    }

    /**
     * @see ProductController::create()
     *
     * @dataProvider provideInvalidData
     *
     * Attempt multiple invalid data submission and assert bad request as status code
     */
    public function testCreateWithInvalidData(array $data): void
    {
        $response = $this->post('/product', $data, 'foo_token');
        $this->assertTrue($response->isClientError());
    }

    /**
     * @see ProductController::create()
     */
    public function testCreate(): void
    {
        $validData = $this->getValidData();
        $response = $this->post('/product', $validData, 'foo_token');
        $this->assertEquals(201, $response->getStatusCode());
        $product = json_decode($response->getContent(), true);
        $this->assertEquals('Valid name', $product['name']);

        // assert product was created; there were 4 in fixtures
        $response = $this->get('/product');
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(5, $data['total']);
    }

    /**
     * @see ProductController::update()
     */
    public function testUpdateWithoutValidToken(): void
    {
        $response = $this->put('/product/1', ['name' => 'Test'], 'Invalid token');
        $this->assertTrue($response->isForbidden());
    }

    /**
     * @see ProductController::update()
     *
     * @dataProvider provideInvalidData
     */
    public function testUpdateWithInvalidData(array $data): void
    {
        $response = $this->put('/product/1', $data, 'foo_token');
        $this->assertTrue($response->isClientError());
    }

    /**
     * @see ProductController::update()
     *
     * Update entity with valid data; response will contain these new values
     */
    public function testUpdateWithValidData(): void
    {
        $validData = $this->getValidData();
        $response = $this->put('/product/1', $validData, 'foo_token');
        $this->assertTrue($response->isOk());
        $product = json_decode($response->getContent(), true);
        $this->assertEquals('Valid name', $product['name']);
    }

    /**
     * @see ProductController::delete()
     */
    public function testDeleteWithInvalidToken(): void
    {
        $response = $this->delete('/product/1', 'Invalid token');
        $this->assertTrue($response->isForbidden());
    }

    /**
     * @see ProductController::delete()
     *
     * Delete entity and assert 404 when requested again
     */
    public function testDelete(): void
    {
        $response = $this->delete('/product/1', 'foo_token');
        $this->assertTrue($response->isEmpty());

        $response = $this->get('/product/1', 'foo_token');
        $this->assertTrue($response->isNotFound());
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
