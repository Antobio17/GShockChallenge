<?php

namespace App\Order\Domain\Model;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Order\Infrastructure\Repository\OrderRepository;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{

    /************************************************* CONSTANTS **************************************************/

    public const THRESHOLD_FIRST_DISCOUNT = 10;
    public const FIRST_DISCOUNT_PERCENT_VALUE = 8;
    public const THRESHOLD_SECOND_DISCOUNT = 20;
    public const SECOND_DISCOUNT_PERCENT_VALUE = 15;

    /************************************************* PROPERTIES *************************************************/

    /**
     * @var int|null
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private ?int $id = NULL;

    /**
     * @var DateTime
     * @ORM\Column(name="order_at", type="datetime", nullable=false)
     */
    private DateTime $orderAt;

    /**
     * @var string
     * @ORM\Column(name="reference", type="string", length=128, unique=true, nullable=false)
     */
    private string $reference;

    /**
     * @var string|null
     * @ORM\Column(name="remarks", type="text", nullable=true)
     */
    private ?string $remarks;

    /**
     * @var float
     * @ORM\Column(name="taxable_income", type="float", nullable=false)
     */
    private float $taxableIncome;

    /**
     * @var float
     * @ORM\Column(name="discount_amount", type="float", nullable=false)
     */
    private float $discountAmount;

    /**
     * @var float
     * @ORM\Column(name="tax", type="float", nullable=false)
     */
    private float $tax;

    /**
     * @var float
     * @ORM\Column(name="total", type="float", nullable=false)
     */
    private float $total;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Order\Domain\Model\OrderLine", mappedBy="order", cascade={"all"})
     */
    private Collection $orderLines;

    /************************************************* CONSTRUCT **************************************************/

    /**
     * Order construct.
     *
     * @param DateTime $orderAt Date of the order.
     * @param string $reference Reference of the order.
     * @param string|null $remarks Remarks of the order.
     */
    public function __construct(DateTime $orderAt, string $reference, ?string $remarks)
    {
        $this->orderAt = $orderAt;
        $this->reference = $reference;
        $this->remarks = $remarks;
        $this->taxableIncome = 0.0;
        $this->discountAmount = 0.0;
        $this->tax = 0.0;
        $this->total = 0.0;

        $this->orderLines = new ArrayCollection();
    }

    /******************************************** GETTERS AND SETTERS *********************************************/

    /**
     * Gets the ID property of the order.
     *
     * @return int int
     */
    public function getID(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the date of the order.
     *
     * @return DateTime DateTime
     */
    public function getDate(): DateTime
    {
        return $this->orderAt;
    }

    /**
     * Gets the reference property of the order.
     *
     * @return string string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * Gets the subtotal price of the order.
     *
     * @return float float
     */
    public function getSubtotal(): float
    {
        return $this->taxableIncome;
    }

    /**
     * Gets the discount percent applied to the order.
     *
     * @return float float
     */
    public function getDiscountPercent(): float
    {
        return ($this->taxableIncome + $this->discountAmount) * 100 / $this->taxableIncome - 100;
    }

    /**
     * Gets the discount amount property of the order.
     *
     * @return float float
     */
    public function getDiscountAmount(): float
    {
        return $this->discountAmount;
    }

    /**
     * Gets the tax property of the order.
     *
     * @return float float
     */
    public function getTax(): float
    {
        return $this->tax;
    }

    /**
     * Gets the total property of the order.
     *
     * @return float float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /*********************************************** PUBLIC METHODS ***********************************************/

    /**
     * Gets the total order lines.
     *
     * @return int int
     */
    public function getNumberOfItems(): int
    {
        return $this->orderLines->count();
    }

    /**
     * Adds a new order line in the collection.
     *
     * @param OrderLine $orderLine The order line to add in the collection.
     */
    public function addOrderLine(OrderLine $orderLine)
    {
        $this->orderLines->add($orderLine);

        # Updating the total counts
        $this->taxableIncome += $orderLine->getTaxableIncome();
        $this->discountAmount += $orderLine->getDiscountAmount();
        $this->tax += $orderLine->getTax();
        $this->total += $orderLine->getTotal();
    }

}