<?php

namespace App\Order\Application\Service;

use App\Order\Application\Model\CreateOrderResponse;
use App\Order\Application\Query\CreateOrderQuery;
use App\Order\Domain\Model\Order;
use App\Order\Domain\Model\OrderLine;
use App\Order\Domain\Repository\OrderRepositoryInterface;
use App\Order\Infrastructure\Api\CreateOrderController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateOrderHandler implements MessageHandlerInterface
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
     * @param CreateOrderQuery $createOrderQuery Query of the creation order.
     *
     * @return CreateOrderResponse CreateOrderResponse
     */
    public function __invoke(CreateOrderQuery $createOrderQuery): CreateOrderResponse
    {
        $order = $this->getOrderRepository()->findByReference($createOrderQuery->getReference());

        if ($order === NULL):
            $order = new Order(
                $createOrderQuery->getOrderAt(), $createOrderQuery->getReference(), $createOrderQuery->getRemarks(),
            );
            foreach ($createOrderQuery->getOrderLines() as $orderLineData):
                $orderLine = new OrderLine(
                    $order, $orderLineData[CreateOrderController::REQUEST_FIELD_PRODUCT],
                    $orderLineData[CreateOrderController::REQUEST_FIELD_QUANTITY],
                    $orderLineData[CreateOrderController::REQUEST_FIELD_AMOUNT],
                    $this->calculateDiscountPercentToApply($createOrderQuery->getOrderLines()),
                    $orderLineData[CreateOrderController::REQUEST_FIELD_IVA],
                );
                $order->addOrderLine($orderLine);
            endforeach;

            $saved = $this->getOrderRepository()->save($order);
            $response = $saved ? CreateOrderResponse::ofSuccess($order) : CreateOrderResponse::ofError(
                Response::HTTP_INTERNAL_SERVER_ERROR, array(
                'An error occurred while saving the entity.'
            ));
        else:
            $response = CreateOrderResponse::ofError(
                Response::HTTP_CONFLICT, array(
                'There is already an order with this reference.'
            ));
        endif;

        return $response;
    }

    /************************************************ PUBLIC METHODS *********************************************/

    /**
     * Calculates the discount percent to apply in the order.
     *
     * @param array $orderLines Data of the order lines.
     *
     * @return float float
     */
    public function calculateDiscountPercentToApply(array $orderLines): float
    {
        $totalAmount = 0.0;
        foreach ($orderLines as $orderLineData):
            $totalAmount += $orderLineData[CreateOrderController::REQUEST_FIELD_AMOUNT] *
                $orderLineData[CreateOrderController::REQUEST_FIELD_QUANTITY];

            if (!isset($discountPercent) && $totalAmount > Order::THRESHOLD_FIRST_DISCOUNT):
                $discountPercent = Order::FIRST_DISCOUNT_PERCENT_VALUE;
            elseif ($totalAmount > Order::THRESHOLD_SECOND_DISCOUNT):
                $discountPercent = Order::SECOND_DISCOUNT_PERCENT_VALUE;
                break;
            endif;
        endforeach;

        return $discountPercent ?? 0.0;
    }

}
