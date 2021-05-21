<?php

namespace App\Repository;

use App\Entity\Payment;
use App\Entity\Campaign;
use App\Entity\Participant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Payment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payment[]    findAll()
 * @method Payment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payment::class);
    }

    // /**
    //  * @return Payment[] Returns an array of Payment objects
    // */

    // public function findAllByCampaignId(Campaign $value)
    // {
    //     return $this->createQueryBuilder('payment')
    //         ->leftJoin('payment.relation', 'participant')
    //         ->leftJoin('participant.campaignId', 'campaign')
    //         ->andWhere('participant.campaignId = :campaign')
    //         ->setParameter('campaign', $value->getId())
    //         ->getQuery()
    //         ->execute();
    //     ;
    // }
  

    /*
    public function findOneBySomeField($value): ?Payment
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
