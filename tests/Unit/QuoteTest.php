<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\QuoteService;
use PHPUnit\Framework\Attributes\Test;

class QuoteTest extends TestCase
{
    protected QuoteService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(QuoteService::class);
    }

    #[Test]
    public function it_calculates_quote_normally()
{
    $payload = [
        'lines' => [
            ['cost' => 50, 'price' => 150, 'qty' => 2],
            ['cost' => 20, 'price' => 100, 'qty' => 1],
        ],
        'labor_hours' => 1,
        'labor_rate' => 5,
        'overhead' => 10,
        'target_margin' => 20,
    ];

    $result = $this->service->calculate($payload);

    $this->assertIsArray($result);
    $this->assertArrayHasKey('lines', $result);
    $this->assertEquals('green', $result['health']); 
}

    #[Test]
    public function it_handles_zero_labor_and_overhead()
    {
        $payload = [
            'lines' => [
                ['cost' => 20, 'price' => 100, 'qty' => 1],
            ],
            'labor_hours' => 0,
            'labor_rate' => 0,
            'overhead' => 0,
            'target_margin' => 10,
        ];

        $result = $this->service->calculate($payload);

        $this->assertEquals(80, $result['netProfit']);
        $this->assertEquals('green', $result['health']);
    }

    #[Test]
    public function it_falls_into_red_health_status_if_margin_far_below_target()
    {
        $payload = [
            'lines' => [
                ['cost' => 90, 'price' => 100, 'qty' => 1],
            ],
            'labor_hours' => 1,
            'labor_rate' => 5,
            'overhead' => 10,
            'target_margin' => 50,
        ];

        $result = $this->service->calculate($payload);

        $this->assertEquals('red', $result['health']);
    }

    #[Test]
    public function it_returns_amber_when_close_to_target_margin()
    {
        $payload = [
            'lines' => [
                ['cost' => 80, 'price' => 100, 'qty' => 1],
            ],
            'labor_hours' => 0,
            'labor_rate' => 0,
            'overhead' => 0,
            'target_margin' => 25,
        ];

        $result = $this->service->calculate($payload);

        $this->assertEquals('amber', $result['health']);
    }

    #[Test]
    public function it_handles_qty_equal_to_zero_gracefully()
    {
        $payload = [
            'lines' => [
                ['cost' => 100, 'price' => 200, 'qty' => 0],
            ],
            'labor_hours' => 0,
            'labor_rate' => 0,
            'overhead' => 0,
            'target_margin' => 30,
        ];

        $result = $this->service->calculate($payload);

        $this->assertEquals(100, $result['netProfit']);
        $this->assertEquals('green', $result['health']);
    }

    #[Test]
    public function it_does_not_crash_when_price_is_zero()
{
    $payload = [
        'lines' => [
            ['cost' => 100, 'price' => 0, 'qty' => 1],
        ],
        'labor_hours' => 0,
        'labor_rate' => 0,
        'overhead' => 0,
        'target_margin' => 30,
    ];

    $result = $this->service->calculate($payload);

    
    $this->assertEquals(-100.0, $result['netProfit']);
    $this->assertEquals(-10000.0, $result['netMargin']);
    $this->assertEquals('red', $result['health']);
}
}
