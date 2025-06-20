<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\ProductController;
use App\Repositories\ProductInterface;
use PHPUnit\Framework\Attributes\Test;

class ProductTest extends TestCase
{
    #[Test]
    public function it_returns_products_as_resource_collection()
    {
        // Arrange: Create fake product data
        $fakeProducts = collect([
            (object)[
                'id' => 1,
                'name' => 'Test Product',
                'cost_price' => 10.5,
                'selling_price' => 20.0,
            ]
        ]);

        // Mock the repository
        $mock = $this->createMock(ProductInterface::class);
        $mock->method('all')->willReturn($fakeProducts);

        // Inject mock into controller
        $controller = new ProductController($mock);

        // Act: Call the method
        $response = $controller->index();

        // Assert: It's a collection of ProductResource
        $this->assertInstanceOf(\Illuminate\Http\Resources\Json\AnonymousResourceCollection::class, $response);
        $this->assertEquals('Test Product', $response->first()->name);
    }
}
