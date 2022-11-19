<?php

namespace App\Order\Application\Query;

use DateTime;

class CreateOrderQuery
{

    /************************************************* PROPERTIES *************************************************/

    /**
     * @var DateTime|false
     */
    private DateTime $orderAt;

    /**
     * @var string
     */
    private string $reference;

    /**
     * @var string|null
     */
    private ?string $remarks;

    /**
     * @var array
     */
    private array $orderLines;

    /************************************************* CONSTRUCT **************************************************/

    /**
     * CreateOrderQuery construct.
     *
     * @param int $orderAtTimestamp Timestamp of the date of the order.
     * @param string $reference Reference of the order.
     * @param string $remarks Remarks of the order.
     * @param array $orderLines Order lines of the order.
     */
    public function __construct(int $orderAtTimestamp, string $reference, string $remarks, array $orderLines)
    {
        $this->orderAt = date_create()->setTimestamp($orderAtTimestamp);
        $this->reference = $reference;
        $this->remarks = $remarks;
        $this->orderLines = $orderLines;
    }

    /******************************************** GETTERS AND SETTERS *********************************************/

    /**
     * Gets the order date of the query.
     *
     * @return DateTime DateTime
     */
    public function getOrderAt(): DateTime
    {
        return $this->orderAt;
    }

    /**
     * Gets the reference of the query.
     *
     * @return string string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * Gets the remarks of the query.
     *
     * @return string|null
     */
    public function getRemarks(): ?string
    {
        return $this->remarks;
    }

    /**
     * Gets the order lines of the query.
     *
     * @return array
     */
    public function getOrderLines(): array
    {
        return $this->orderLines;
    }

}
