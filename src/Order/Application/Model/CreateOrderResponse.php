<?php

namespace App\Order\Application\Model;

use App\Order\Domain\Model\Order;

class CreateOrderResponse
{

    /************************************************* PROPERTIES *************************************************/

    /**
     * @var Order|null
     */
    private ?Order $order;

    /**
     * @var array
     */
    private array $messages;

    /************************************************* CONSTRUCT **************************************************/

    /**
     * CreateOrderResponse construct.
     *
     * @param Order|null $order Order of the response or null if an error occurred.
     * @param array $messages Messages of the response.
     */
    private function __construct(?Order $order, array $messages)
    {
        $this->order = $order;
        $this->messages = $messages;
    }

    /******************************************** GETTERS AND SETTERS *********************************************/

    /**
     * Gets the order of the response.
     *
     * @return Order|null Order|null
     */
    public function getOrder(): ?Order
    {
        return $this->order;
    }

    /**
     * Gets the messages of the response.
     *
     * @return array array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /*********************************************** STATIC METHODS ************************************************/

    /**
     * Facade to create a success response.
     *
     * @param Order $order
     *
     * @return self self
     */
    public static function ofSuccess(Order $order): self
    {
        return new self($order, array('Created'));
    }

    /**
     * Facade to create a success response.
     *
     * @param array $messages
     *
     * @return self self
     */
    public static function ofError(array $messages): self
    {
        return new self(NULL, $messages);
    }

}
