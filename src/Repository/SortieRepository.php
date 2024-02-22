<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
    public function filterSorties($idSite, $nomContient, $dateDebut, $dateFin, $estOrganisateur, $estInscrit, $nEstPasInscrit, $estPassee): array
    {


        $qb = $this->createQueryBuilder('sortie')
                ->where('sortie.site = :idSite')
                ->setParameter('idSite', $idSite);

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
            $qb->andWhere('sortie.organisateur.id = :userId')
                ->setParameter('userId', $this->getUser()->getId());
        }



        return $qb
            ->orderBy('sortie.dateHeureDebut', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
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
