<?php

namespace App\Order\Application\Model;

use App\Order\Domain\Model\Order;
use Symfony\Component\HttpFoundation\Response;

class CreateOrderResponse
{

    /************************************************* PROPERTIES *************************************************/

    /**
     * @var Order|null
     */
    private ?Order $order;

    /**
     * @var int
     */
    private int $code;

    /**
     * @var array
     */
    private array $messages;

    /************************************************* CONSTRUCT **************************************************/

    /**
     * CreateOrderResponse construct.
     *
     * @param Order|null $order Order of the response or null if an error occurred.
     * @param int $code The code of the response.
     * @param array $messages Messages of the response.
     */
    private function __construct(?Order $order, int $code, array $messages)
    {
        $this->order = $order;
        $this->code = $code;
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
     * Gets the code of the response.
     *
     * @return int int
     */
    public function getCode(): int
    {
        return $this->code;
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
        return new self($order, Response::HTTP_CREATED, array('Created'));
    }

    /**
     * Facade to create a success response.
     *
     * @param int $code
     * @param array $messages
     *
     * @return self self
     */
    public static function ofError(int $code, array $messages): self
    {
        return new self(NULL, $code, $messages);
    }

}
