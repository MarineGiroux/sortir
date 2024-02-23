<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription', methods: ['GET'], requirements: ['idSortie' => '\d+', 'etatSortie'=> '/^.{1,50}$/'])]
    public function inscription(Request $request,
                                EntityManagerInterface $em,
                                #[MapQueryParameter] int $idSortie,
                                #[MapQueryParameter] string $etatSortie,


    ): Response
    {
        //recuperer user et son id
        $idUser= $this->getUser()->getId();
        //recupérer sortie :
        // id,
        //$idSortie
        // etat,

        // liste participants
        //verifier si déjà inscrit
        //vérifier si date déjà passée => si l'état de la sortie est compatible
        if($idSortie != 'Ouverte'){

        }
        //vérifier si nombre d'inscrits pas dépassé

        return $this->redirectToRoute('app_home');
    }

    #[Route('/desistement', name: 'desistement')]
    public function desistement(): Response
    {
        return $this->redirectToRoute('app_home');
    }

}
