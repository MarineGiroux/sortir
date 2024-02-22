<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function inscription(): Response
    {



        return $this->redirectToRoute('app_home');
    }

    #[Route('/desistement', name: 'desistement')]
    public function desistement(): Response
    {
        return $this->redirectToRoute('app_home');
    }

}
