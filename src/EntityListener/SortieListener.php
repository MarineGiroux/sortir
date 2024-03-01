<?php

namespace App\EntityListener;

use App\Entity\Sortie;
use App\Repository\EtatRepository;
use Doctrine\ORM\EntityManagerInterface;

class SortieListener
{
    private EtatRepository $etatRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(EtatRepository $etatRepository, EntityManagerInterface $entityManager)
    {
        $this->etatRepository = $etatRepository;
        $this->entityManager = $entityManager;
    }


    public function postLoad(Sortie $sortie): void
    {
        // récupération de la date actuelle
        $heureActuelle = new \DateTime();
        // conversion de l'heure actuelle en timestamps et retrait de 1h pour le fuseau horaire
        $heureActuelleInt = ($heureActuelle->getTimestamp())-3600;
        // récupération de la date actuelle - 1 mois
        $oneMonthAgo = new \DateTime('-1 month');
        // récupération horaire de début
        $heureDeDebut = $sortie->getDateHeureDebut();
        // conversion heure début int en timestamps et retrait de 1h pour le fuseau horaire
        $heureDeDebutInt = ($heureDeDebut->getTimestamp()-3600);
        // Calcul de l'heure de fin en timestamp (heure de début + durée en minutes concerties en seconde (*60)) et soustraction d'une heure
        $heureDeFinInt = ($heureDeDebutInt + ($sortie->getDuree() * 60))-3600;

        //  Si la date début est < à 1 mois)
        if ($sortie->getDateHeureDebut() < $oneMonthAgo) {
            // Récupération de l'état "Archivée" depuis le référentiel des états
            $etatArchivee = $this->etatRepository->findOneBy(['id' => '3']);
            // Si la sortie est pas déjà archivée : mettre à l'état archivée
            if ($sortie->getEtat() !== $etatArchivee) {
                $sortie->setEtat($etatArchivee);
            }

        //  Si la date actuelle est >= à la date début et la date actuelle est <= à l'heure de fin de sortie)
        } else if ($heureActuelleInt >= $heureDeDebutInt && $heureActuelleInt <= $heureDeFinInt) {
            // Récupération de l'état "En cours" depuis le référentiel des états
            $etatEnCours = $this->etatRepository->findOneBy(['id' => '4']);
            // Si la sortie est pas déjà En cours : mettre à l'état En cours
            if ($sortie->getEtat() !== $etatEnCours) {
                $sortie->setEtat($etatEnCours);
            }

        //  Si la date début est < à la date actuelle
        } else if ($heureDeFinInt < $heureActuelleInt) {
            // Récupération de l'état "Passée" depuis le référentiel des états
            $etatPassee = $this->etatRepository->findOneBy(['id' => '5']);
            // Si la sortie est pas déjà Passée : mettre à l'état Passée
            if ($sortie->getEtat() !== $etatPassee) {
                $sortie->setEtat($etatPassee);
            }
        }
        // Persiste les changements dans la base de données
        $this->entityManager->persist($sortie);
        $this->entityManager->flush();

    }
}