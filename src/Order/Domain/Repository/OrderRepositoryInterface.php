<?php

namespace App\Order\Domain\Repository;

use App\Order\Domain\Model\Order;

interface OrderRepositoryInterface
{

    /**
     * Finds a Order by the reference string.
     *
     * @param string $reference The reference string to search the product.
     *
     * @return Order|null Order|null
     */
    public function findByReference(string $reference): ?Order;

    /**
     * Saves the entity Order passed.
     *
     * @param Order $order The order to save.
     *
     * @return bool bool
     */
    public function save(Order $order): bool;

}