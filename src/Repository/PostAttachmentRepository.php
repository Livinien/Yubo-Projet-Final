<?php

namespace App\Repository;

use App\Entity\PostAttachment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostAttachment>
 *
 * @method PostAttachment|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostAttachment|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostAttachment[]    findAll()
 * @method PostAttachment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostAttachmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostAttachment::class);
    }

//    /**
//     * @return PostAttachment[] Returns an array of PostAttachment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PostAttachment
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
