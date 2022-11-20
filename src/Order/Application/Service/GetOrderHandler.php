<?php

namespace App\Order\Application\Service;

use App\Order\Application\Model\GetOrderResponse;
use App\Order\Application\Query\GetOrderQuery;
use App\Order\Domain\Repository\OrderRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetOrderHandler implements MessageHandlerInterface
{

    /************************************************* PROPERTIES *************************************************/

    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;

    /************************************************* CONSTRUCT **************************************************/

    /**
     * CreateOrderHandler construct.
     *
     * @param OrderRepositoryInterface $orderRepository Repository of the order model.
     */
    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /******************************************** GETTERS AND SETTERS *********************************************/

    /**
     * Gets the order repository.
     *
     * @return OrderRepositoryInterface OrderRepositoryInterface
     */
    public function getOrderRepository(): OrderRepositoryInterface
    {
        return $this->orderRepository;
    }

    /************************************************ INVOKE METHOD ***********************************************/

    /**
     * CreateOrderController invoke.
     *
     * @param GetOrderQuery $getOrderQuery Query of the request.
     *
     * @return GetOrderResponse GetOrderResponse
     */
    public function __invoke(GetOrderQuery $getOrderQuery): GetOrderResponse
    {
        return GetOrderResponse::ofSuccess($this->getOrderRepository()->findAll());
    }

}
