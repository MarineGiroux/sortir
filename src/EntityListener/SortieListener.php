<?php

namespace App\EntityListener;

use App\Entity\Sortie;
use App\Repository\EtatRepository;
use Doctrine\ORM\EntityManager;

class SortieListener
{
    public function __construct( private readonly EtatRepository $etatRepository, EntityManager $em){

    }

    public function postLoad(Sortie $sortie):void
    {
        $etat = $this->etatRepository->findOneBy(['libelle' => 'Passée']);

        if($sortie->getDateHeureDebut() < new \DateTime()){
            $sortie->setEtat($etat);
        }
    }

}