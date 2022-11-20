<?php

namespace App\Order\Application\Model;

/**
 * GetOrderResponse.
 */
class GetOrderResponse
{

    /************************************************* PROPERTIES *************************************************/

    /**
     * @var array
     */
    private array $orders;

    /************************************************* CONSTRUCT **************************************************/

    /**
     * GetOrderResponse construct.
     *
     * @param array $orders Order of the response or null if an error occurred.
     */
    private function __construct(array $orders)
    {
        $this->orders = $orders;
    }

    /******************************************** GETTERS AND SETTERS *********************************************/

    /**
     * Gets the orders of the response.
     *
     * @return array array
     */
    public function getOrders(): array
    {
        return $this->orders;
    }

    /*********************************************** STATIC METHODS ************************************************/

    /**
     * Facade to create a success response.
     *
     * @param array $orders
     *
     * @return self self
     */
    public static function ofSuccess(array $orders): self
    {
        return new self($orders);
    }

}
