<?php

namespace App\Controller;

use App\Entity\Sortie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class InscriptionController extends AbstractController
{
    #[Route('/inscription/{id}', name: 'app_inscription', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function inscription(Sortie $sortie, Request $request, EntityManagerInterface $em): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Liste des participants
        $participants = $sortie->getUsers()->toArray();

        // Vérifier les conditions d'inscription
        if (
            $sortie->getEtat()->getLibelle() == 'Ouverte'
            && count($participants) < $sortie->getNbInscriptionMax()
            && $sortie->getDateLimiteInscription() > new \DateTime()
            && !in_array($user, $participants, true)
        ) {
            $sortie->addUser($user);
            $em->persist($sortie);
            $em->flush();

            return $this->redirectToRoute('app_home', [
                'userInscrit' => $user,
            ]);
        }

        // Rediriger vers la page d'accueil
        return $this->redirectToRoute('app_home');
    }


    #[Route('/desistement/{id}', name: 'app_desistement', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function desistement(Sortie $sortie, EntityManagerInterface $em): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Liste des participants
        $participants = $sortie->getUsers()->toArray();

        // Vérifier si l'utilisateur est inscrit
        if (in_array($user, $participants, true)) {
            // Retirer l'utilisateur de la liste des participants
            $sortie->removeUser($user);
            $em->persist($sortie);
            $em->flush();

            // Rediriger vers la page d'accueil ou une autre page
            return $this->redirectToRoute('app_home', [
                'userDesiste' => $user,
            ]);
        }

        // Rediriger vers la page d'accueil si l'utilisateur n'est pas inscrit
        return $this->redirectToRoute('app_home');
    }

}
