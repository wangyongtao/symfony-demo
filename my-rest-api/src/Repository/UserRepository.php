<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     *
     * @param array $criteria
     * @param int $page
     * @param int $limit
     * @return float|int|mixed|string
     */
    public function findByFilter(array $criteria, int $page, int $perPage = 20): mixed
    {
        $isActive = $criteria['is_active'] ?? -1;
        $isMember = $criteria['is_member'] ?? -1;
        $userType = trim($criteria['user_type'] ?? '');
        $startTime = $criteria['login_start_Time'] ?? "";
        $endTime = $criteria['login_end_Time'] ?? "";
        $perPage = min($perPage, 1000); // per page limit

        $offset = ($page - 1) > 0 ? $page * $perPage : 0;
        $qb =  $this->createQueryBuilder('u')
            ->select(
                'u.id,u.username,u.is_active,u.is_member,u.user_type,u.last_login_at'
            )
            ->orderBy('u.id', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($perPage);
        if ($isActive > -1) {
            $qb->andWhere('u.is_active = :is_active')->setParameter('is_active', $isActive);
        }
        if ($isMember > -1) {
            $qb->andWhere('u.is_member = :is_member')->setParameter('is_member', $isMember);
        }
        if ($userType) {
            $types = explode(',', trim($userType));
            $validTypes = get_valid_user_types($types);
            $str = $validTypes ? implode(',', $validTypes) : '-1';
            $qb->andWhere('u.user_type IN (:user_type)')->setParameter('user_type', $str);
        }
        if ($startTime > 0 && $endTime > 0) {
            $qb->andWhere('u.last_login_at >= :startTime')
                ->setParameter('startTime', $startTime)
                ->andWhere('u.last_login_at <= :endTime')
                ->setParameter('endTime', $endTime);
        }

        $query = $qb->getQuery();
//        print_r($qb->getDQL());
//        exit;
        return $query->execute();
    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
