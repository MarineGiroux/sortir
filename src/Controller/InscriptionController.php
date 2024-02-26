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
    public function inscription (sortie $sortie,
                                 Request $request,
                                 EntityManagerInterface $em,
                                    #[MapQueryParameter] string $etatSortie,


    ): Response{

        //recuperer user
        $user = $this->getUser();

        // liste participants
        //to array pour exploiter cette "persistant collection"
        $participants = $sortie->getUsers()->toArray();
        //verifier si déjà inscrit, si le nombre d'instrits n'est pas atteint et si l'user n'est pas déjà inscrit
        if($sortie->getEtat()->getLibelle() == 'Ouverte' && count($participants) < $sortie->getNbInscriptionMax() && !in_array($user-> getEmail(), $participants, true)){
            $sortie->addUser($user);
            $em->persist($sortie);
            $em->flush();
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
