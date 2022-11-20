<?php

namespace App\Tests\Unit\Service;

use App\Order\Application\Service\CreateOrderHandler;
use App\Order\Domain\Model\Order;
use App\Order\Infrastructure\Repository\OrderRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateOrderHandlerTest extends TestCase
{

    /************************************************* PROPERTIES *************************************************/

    /**
     * @var MockObject|OrderRepository
     */
    private OrderRepository $orderRepository;

    private CreateOrderHandler $service;

    /************************************************ TEST METHODS ************************************************/

    /**
     * Tests the method CalculateDiscountPercentToApply of the order creation service.
     */
    public function testCalculateDiscountPercentToApply(): void
    {
        $orderLines[] = array('product' => 'Product Name 1', 'quantity' => 2, 'amount' => 2.3, 'iva' => 21);
        $discountPercent = $this->service->calculateDiscountPercentToApply($orderLines);
        self::assertEquals(0, $discountPercent);

        $orderLines[] = array('product' => 'Product Name 2', 'quantity' => 3, 'amount' => 2.3, 'iva' => 21);
        $discountPercent = $this->service->calculateDiscountPercentToApply($orderLines);
        self::assertEquals(Order::FIRST_DISCOUNT_PERCENT_VALUE, $discountPercent);

        $orderLines[] = array('product' => 'Product Name 3', 'quantity' => 3, 'amount' => 2.3, 'iva' => 21);
        $discountPercent = $this->service->calculateDiscountPercentToApply($orderLines);
        self::assertEquals(Order::FIRST_DISCOUNT_PERCENT_VALUE, $discountPercent);
    }

    /*********************************************** PUBLIC METHODS ***********************************************/

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->orderRepository = $this->getMockBuilder(OrderRepository::class)->disableOriginalConstructor()
            ->getMock();
        $this->service = new CreateOrderHandler($this->orderRepository);
    }

}
