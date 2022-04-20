<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Sortie $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Sortie $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


      /**
      * @return Sortie[] Returns an array of Sortie objects
      */

    public function findAllDate()
    {
        return $this->createQueryBuilder('s')
            ->andWhere('DATE_DIFF(CURRENT_DATE(), s.dateHeureDebut) < 30')
            ->getQuery()
            ->getResult()
        ;
    }

    public function figndOneByDate()
    {
        return $this->createQueryBuilder('s')
            ->andWhere('DATE_DIFF(CURRENT_DATE(), s.dateHeureDebut) < 30')
            ->getQuery()
            ->getResult()
            ;
    }


    public function findOneByDate($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.id = :val')
            ->andWhere('DATE_DIFF(CURRENT_DATE(), s.dateHeureDebut) < 30')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function filter($value, $user) {
        $query = $this->createQueryBuilder('s')
        ->leftJoin('s.user', 'u');

        if (!empty($value['campus'])) {
            $query = $query->andWhere('s.site = :site')
            ->setParameter('site', $value['campus']);
        }

        if (!empty($value['name'])) {
            $query = $query->andWhere('s.nom LIKE :search')
                ->setParameter('search', "%{$value['name']}%");
        }

        if (!empty($value['sortie_organisateur'])) {
            $query = $query->andWhere('s.organisateur = :user')
                ->setParameter('user', $user);

        }

        if (!empty($value['sortie_inscrite'])) {
            $query = $query->andWhere('u = :user')
                ->setParameter('user', $user);
        }

        if (!empty($value['sortie_non_inscrite'])) {

        }

        if (!empty($value['sortie_passees'])) {
            // Un événement débute le 20/04 00h et qui dure 30 min est clotué à 00h31
            $query = $query->andWhere("DATE_ADD(s.dateHeureDebut, s.duree, 'MINUTE') < CURRENT_TIMESTAMP()");
        }

        return $query->getQuery()->getResult();
    }
}
