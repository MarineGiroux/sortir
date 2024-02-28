<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\CountWalker;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
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
     * @return Sortie[] Returns an array of Sortie objects
     */
    public function filterSorties($user, $siteParDefautOuPas,$site, $nomContient, $dateDebut, $dateFin, $estOrganisateur, $inscritOuPas,$estPassee):array
    {
        $qb = $this->createQueryBuilder('sorties')
            ->leftJoin('sorties.users', 'users');

        if ($user) {
            if ($siteParDefautOuPas == 'conserverSite') {
                $qb->andWhere('sorties.site = :site')
                    ->setParameter('site', $user->getSite());
            } elseif ($siteParDefautOuPas == 'choisirSite') {
                $qb->andWhere('sorties.site = :site')
                    ->setParameter('site', $site);
            }
        }

        if ($nomContient) {
            $qb->andWhere("sorties.nomSortie LIKE :nomContient")
                ->setParameter('nomContient', '%' . $nomContient . '%');
        }

        if($dateDebut){
            $qb->andWhere('sorties.dateHeureDebut >= :dateDebut')
                ->setParameter('dateDebut', $dateDebut);
        }

        if($dateFin){
            $qb->andWhere('sorties.dateHeureDebut <= :dateFin')
                ->setParameter('dateFin', $dateFin);
        }

        if ($estOrganisateur) {
            $qb->andWhere('sorties.organisateur = :user')
                ->setParameter('user', $user);
        }

//        if ($inscrit) {
//            $qb->andWhere('users = :user')
//                ->setParameter('user', $user);
//        }
//
//        if ($nonInscrit) {
//            $qb->andWhere('users != :user')
//                ->setParameter('user', $user);
//        }


//            if($inscritOuPas == 'nonInscrit'){
//                $qb->andWhere( 'users NOT IN (:users)')
//                    ->setParameter('users', array($user));
//            }
//            $result = $entityManager->createQuery("SELECT e FROM $entity e
//        WHERE e.ID NOT IN (:ids)")
//                ->setParameter('ids', array(1, 2, 3), \Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
//                ->getResult();


        if ($estPassee) {
            $qb->andWhere("sorties.etat ='Passée'" );
        }


        if($inscritOuPas){
            if($inscritOuPas == 'inscrit'){
                return $qb->andWhere('users = :user')
                    ->setParameter('user', $user)
                    ->orderBy('sorties.dateHeureDebut', 'ASC')
                    ->setMaxResults(10)
                    ->getQuery()
                    ->getResult();
            }
            if($inscritOuPas == 'nonInscrit'){

                $qb2 = $qb;
                $toutesLesSorties = $qb
                    ->orderBy('sorties.dateHeureDebut', 'ASC')
                    ->setMaxResults(10)
                    ->getQuery()
                    ->getResult();
                //dd($toutesLesSorties);


                $sortiesOuInscrit = $qb2
                    ->andWhere('users = :user')
                    ->setParameter('user', $user)
                    ->getQuery()
                    ->getResult();
                //dd($sortiesOuInscrit);

                $sortiesPasInscrit = array_udiff($toutesLesSorties,$sortiesOuInscrit, function ($obj_a, $obj_b) {
                    return $obj_a->getId() - $obj_b->getId();
                });
                //dd($sortiesPasInscrit);
                return $sortiesPasInscrit;

            }
        }

        return $qb
            ->orderBy('sorties.dateHeureDebut', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }


    public function getAllSortiesWithUsers($user):array {
//        dd($user->getSite());

        if (!$user){
            $qb = $this->createQueryBuilder('sorties')
                ->addSelect('users')
                ->leftJoin('sorties.users', 'users');
            return $qb
                ->getQuery()
                ->getResult();
        }

        $qb = $this->createQueryBuilder('sorties')
            ->addSelect('users')
            ->leftJoin('sorties.users', 'users')
            ->andWhere('sorties.site = :site')
            ->setParameter('site', $user->getSite())
        ;
        return $qb
            ->getQuery()
            ->getResult();
    }

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
