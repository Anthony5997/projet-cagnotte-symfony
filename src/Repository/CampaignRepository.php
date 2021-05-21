<?php

namespace App\Repository;

use App\Entity\Campaign;
use App\Entity\Participant;
use App\Entity\Payment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Campaign|null find($id, $lockMode = null, $lockVersion = null)
 * @method Campaign|null findOneBy(array $criteria, array $orderBy = null)
 * @method Campaign[]    findAll()
 * @method Campaign[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CampaignRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Campaign::class);
    }

    /**
    * @return Payment[] Returns an array of Payment objects
    */

    public function findAllByCampaignId(Campaign $value)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT DISTINCT
                payment,
                participant,
                campaign
            FROM
                App\Entity\Payment payment
            JOIN
                payment.relation participant
            JOIN
                participant.campaignId campaign
            WHERE
                campaign.id = :value
            '
        )->setParameter('value', $value->getId());;
        return $query->getResult();
    }

        /**
    * @return Payment[] Returns an array of Payment objects
    */

    public function sumPayment(Campaign $value)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT SUM(payment.amount)
              
            FROM
                App\Entity\Payment payment
            JOIN
                payment.relation participant
            JOIN
                participant.campaignId campaign
            WHERE
                campaign.id = :value
            '
        )->setParameter('value', $value->getId());;
        return $query->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Campaign
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
