<?php

namespace App\Order\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Entity()
 */
class OrderLine
{

    /************************************************* PROPERTIES *************************************************/

    /**
     * @var int|null
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private ?int $id = NULL;

    /**
     * @var string
     * @ORM\Column(name="product", type="string", length=512, nullable=false)
     */
    private string $product;

    /**
     * @var float
     * @ORM\Column(name="quantity", type="float", nullable=false)
     */
    private float $quantity;

    /**
     * @var float
     * @ORM\Column(name="amount", type="float", nullable=false)
     */
    private float $amount;

    /**
     * @var float
     * @ORM\Column(name="taxable_income", type="float", nullable=false)
     */
    private float $taxableIncome;

    /**
     * @var float
     * @ORM\Column(name="discount_percent", type="float", nullable=false)
     */
    private float $discountPercent;

    /**
     * @var float
     * @ORM\Column(name="discount_amount", type="float", nullable=false)
     */
    private float $discountAmount;

    /**
     * @var float
     * @ORM\Column(name="iva", type="float", nullable=false)
     */
    private float $IVA;

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
     * @ManyToOne(targetEntity="App\Order\Domain\Model\Order", inversedBy="orderLines")
     * @JoinColumn(name="`order`", referencedColumnName="id", nullable=false)
     */
    protected Order $order;

    /************************************************* CONSTRUCT **************************************************/

    /**
     * OrderLine construct.
     *
     * @param Order $order Order to which it belongs.
     * @param string $product Product of the order line.
     * @param float $quantity Quantity of the order line.
     * @param float $amount Amount of the order line.
     * @param float $discountPercent Discount percent of the order line.
     * @param float $IVA IVA of the order line.
     */
    public function __construct(Order $order, string $product, float $quantity, float $amount, float $discountPercent,
                                float $IVA)
    {
        $this->order = $order;
        $this->product = $product;
        $this->quantity = $quantity;
        $this->amount = $amount;
        $this->IVA = $IVA;
        $this->discountPercent = $discountPercent;
        $this->discountAmount = $this->calculateDiscountAmount();
        $this->taxableIncome = $this->calculateTaxableIncome();
        $this->tax = $this->calculateTax();
        $this->total = $this->calculateTotal();
    }

    /******************************************** GETTERS AND SETTERS *********************************************/

    /**
     * Gets the taxable income property of the order line.
     *
     * @return float float
     */
    public function getTaxableIncome(): float
    {
        return $this->taxableIncome;
    }

    /**
     * Gets the discount amount property of the order line.
     *
     * @return float float
     */
    public function getDiscountAmount(): float
    {
        return $this->discountAmount;
    }

    /**
     * Gets the tax property of the order line.
     *
     * @return float float
     */
    public function getTax(): float
    {
        return $this->tax;
    }

    /**
     * Gets the total property of the order line.
     *
     * @return float float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * Gets the amount property of the order line.
     *
     * @return float float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Gets the discount percent property of the order line.
     *
     * @return float float
     */
    public function getDiscountPercent(): float
    {
        return $this->discountPercent;
    }

    /**
     * Gets the IVA property of the order line.
     *
     * @return float float
     */
    public function getIVA(): float
    {
        return $this->IVA;
    }

    /*********************************************** PUBLIC METHODS ************************************************/

    /**
     * Calculates the taxable income of the order line.
     *
     * @return float float
     */
    public function calculateTaxableIncome(): float
    {
        return $this->getAmount() * $this->quantity - $this->getDiscountAmount();
    }

    /**
     * Calculates the discount amount of the order line.
     *
     * @return float float
     */
    public function calculateDiscountAmount(): float
    {
        return $this->getAmount() * $this->quantity * $this->getDiscountPercent() / 100;
    }

    /**
     * Calculates the tax of the order line.
     *
     * @return float float
     */
    public function calculateTax(): float
    {
        return $this->getTaxableIncome() * $this->getIVA() / 100;
    }

    /**
     * Calculates the total of the order line.
     *
     * @return float float
     */
    public function calculateTotal(): float
    {
        return $this->getTaxableIncome() + $this->getTax();
    }

}