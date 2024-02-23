<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(SortieRepository $sortieRepository): Response
    {
        $sortie = $sortieRepository->findAll();

        return $this->render('sortie/index.html.twig', [
            'sortie'=>$sortie,
        ]);
    }




}

