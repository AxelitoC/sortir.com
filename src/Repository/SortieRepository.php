<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\Entity\User;
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
        $query = $this->createQueryBuilder('s');

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

        if (!empty($value['entre'])) {
            $date = new \DateTime();
            $date->setDate($value['entre']['year'], $value['entre']['month'], $value['entre']['day']);

            $query = $query->andWhere('s.dateHeureDebut > :entre')
                ->setParameter('entre', $date->format('Y-m-d'));
        }

        if (!empty($value['et'])) {
            $date = new \DateTime();
            $date->setDate($value['et']['year'], $value['et']['month'], $value['et']['day']);
            $date->format('Y-m-d');

            $query = $query->andWhere('s.dateHeureDebut < :et')
                ->setParameter('et', $date->format('Y-m-d'));
        }

        if (!empty($value['sortie_inscrite'])) {
            $ni = $this->getEntityManager()->getRepository(User::class)->find($user->getId());
            $query = $query->andWhere(':user MEMBER OF s.user')->setParameter('user', $ni);
        }

        if (!empty($value['sortie_non_inscrite'])) {
            $ni = $this->getEntityManager()->getRepository(User::class)->find($user->getId());
            $query = $query->andWhere(':user NOT MEMBER OF s.user')->setParameter('user', $ni);
        }

        if (!empty($value['sortie_passees'])) {
            // Un événement débute le 20/04 00h et qui dure 30 min est clotué à 00h31
            $query = $query->andWhere("DATE_ADD(s.dateHeureDebut, s.duree, 'MINUTE') < CURRENT_TIMESTAMP()");
        }

        return $query->getQuery()->getResult();
    }
}
