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
    public function filterSorties($idUser, $idSite, $nomContient, $dateDebut, $dateFin, $estOrganisateur): array
    {

        // Example - $qb->leftJoin('u.Phonenumbers', 'p', 'WITH', 'p.area_code = 55', 'p.id')
        //public function leftJoin($join, $alias, $conditionType = null, $condition = null, $indexBy = null);

        $qb = $this->createQueryBuilder('sortie')
                ->leftJoin('sortie.users', 'users')
                ->where('sortie.site = :idSite')
                ->setParameter('idSite', $idSite)
        ;

        if($nomContient){
            $qb->andWhere("sortie.nomSortie LIKE :nomContient")
                ->setParameter('nomContient', '%'.$nomContient.'%');
        }

        if($dateDebut && $dateFin){
            $qb->andWhere('sortie.dateHeureDebut BETWEEN :dateDebut AND :dateFin')
                    ->setParameter('dateDebut', $dateDebut)
                    ->setParameter('dateFin', $dateFin);
            }
//        $estOrganisateur =$form->get('organisateurOuPas')->getData();
//        $estInscrit =$form->get('inscritOuPas')->getData();
//        $nEstPasInscrit =$form->get('nonInscritOuPas')->getData();
//        $estPassee =$form->get('passeesOuPas')->getData();

        if($estOrganisateur){
            $qb->andWhere('sortie.organisateur = :idUser')
                ->setParameter('idUser', $idUser);
        }

        return $qb
            ->orderBy('sortie.dateHeureDebut', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getAllSortiesWithUsers() {

        $qb = $this->createQueryBuilder('sorties')
            ->addSelect('users')
            ->leftJoin('sorties.users', 'users')
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
