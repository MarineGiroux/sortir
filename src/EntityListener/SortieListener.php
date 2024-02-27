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
        $heureActuelle = new \DateTime();
        $heureActuelleInt = ($heureActuelle->getTimestamp())-3600;
        $oneMonthAgo = new \DateTime('-1 month');
        $heureDeDebut = $sortie->getDateHeureDebut();
        $heureDeDebutInt = ($heureDeDebut->getTimestamp()-3600);
        $heureDeFinInt = ($heureDeDebutInt + ($sortie->getDuree() * 60))-3600;


        if ($sortie->getDateHeureDebut() < $oneMonthAgo) {
            // Si l'heure actuelle est avant un mois auparavant, la sortie est archivée
            $etatArchivee = $this->etatRepository->findOneBy(['id' => '3']);

            if ($sortie->getEtat() !== $etatArchivee) {
                $sortie->setEtat($etatArchivee);
            }

        } else if ($heureActuelleInt >= $heureDeDebutInt && $heureActuelleInt <= $heureDeFinInt) {
            // Si l'heure actuelle est entre l'heure de début et l'heure de fin, la sortie est en cours
            $etatEnCours = $this->etatRepository->findOneBy(['id' => '4']);

            if ($sortie->getEtat() !== $etatEnCours) {
                $sortie->setEtat($etatEnCours);
            }

        } else if ($sortie->getDateHeureDebut() < $heureActuelle) {
            // Si l'heure actuelle est après l'heure de fin, la sortie est passée
            $etatPassee = $this->etatRepository->findOneBy(['id' => '5']);

            if ($sortie->getEtat() !== $etatPassee) {
                $sortie->setEtat($etatPassee);
            }
        }
        $this->entityManager->persist($sortie);
        $this->entityManager->flush();

    }
}