<?php

namespace App\Order\Infrastructure\Repository;

use App\Order\Domain\Model\Order;
use App\Order\Domain\Repository\OrderRepositoryinterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository implements OrderRepositoryinterface
{

    /************************************************* CONSTRUCT **************************************************/

    /**
     * OrderRepository construct.
     *
     * @param ManagerRegistry $registry Manager registry to manage the doctrine.
     *
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /*********************************************** PUBLIC METHODS ************************************************/

    /**
     * @inheritDoc
     * @return Order|null Order|null
     */
    public function findByReference(string $reference): ?Order
    {
        $alias = 'odr';

        try {
            $order = $this->createQueryBuilder($alias)
                ->andWhere($alias . '.reference = :reference')
                ->setParameter('reference', $reference)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }

        return $order ?? NULL;
    }

    /**
     * @inheritDoc
     * @return bool bool
     */
    public function save(Order $order): bool
    {
        try {
            if ($order->getID() === NULL):
                $this->getEntityManager()->persist($order);
            endif;

            $this->getEntityManager()->flush();
        } catch (Exception $e) {
            $saved = FALSE;
        }

        return $saved ?? TRUE;
    }

}