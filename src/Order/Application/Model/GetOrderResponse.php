<?php

namespace App\Order\Application\Model;

use Symfony\Component\HttpFoundation\Response;

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

    /**
     * @var int
     */
    private int $code;

    /************************************************* CONSTRUCT **************************************************/

    /**
     * GetOrderResponse construct.
     *
     * @param array $orders Order of the response or null if an error occurred.
     * @param int $code The code of the response.
     */
    private function __construct(array $orders, int $code)
    {
        $this->orders = $orders;
        $this->code = $code;
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

    /**
     * Gets the code of the response.
     *
     * @return int int
     */
    public function getCode(): int
    {
        return $this->code;
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
        return new self($orders, Response::HTTP_OK);
    }

}
