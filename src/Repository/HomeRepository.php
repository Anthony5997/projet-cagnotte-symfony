<?php

namespace App\Repository;

use App\Entity\Home;
use App\Entity\Campaign;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Home|null find($id, $lockMode = null, $lockVersion = null)
 * @method Home|null findOneBy(array $criteria, array $orderBy = null)
 * @method Home[]    findAll()
 * @method Home[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HomeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Home::class);
    }

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
}
