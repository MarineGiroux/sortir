<?php

namespace App\EntityListener;

use App\Entity\Sortie;
use App\Repository\EtatRepository;
use Doctrine\ORM\EntityManager;

class SortieListener
{
    public function __construct( private readonly EtatRepository $etatRepository, EntityManager $em){

    }


    public function postLoad(Sortie $sortie): void
    {
        $passee = new \DateTime();

        $oneMonthAgo = new \DateTime('-1 month');
        if ($sortie->getDateHeureDebut() < $oneMonthAgo) {
            $etatArchivee = $this->etatRepository->findOneBy(['libelle' => 'Archivée']);

            if ($sortie->getEtat() !== $etatArchivee) {
                $sortie->setEtat($etatArchivee);

            }
        } else if ($sortie->getDateHeureDebut() < $passee){
            $etatPassee = $this->etatRepository->findOneBy(['libelle' => 'Passée']);

            if ($sortie->getEtat() !== $etatPassee) {
                $sortie->setEtat($etatPassee);

            }

        }
    }

//    public function postLoadEnCours(Sortie $sortie):void
//    {
//        $etat = $this->etatRepository->findOneBy(['libelle' => 'Activité en cours']);
//
//        if($sortie->getDateHeureDebut() < new \DateTime()){
//            $sortie->setEtat($etat);
//        }
//    }



}