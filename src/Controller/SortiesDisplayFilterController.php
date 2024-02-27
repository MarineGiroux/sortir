<?php

namespace App\Controller;

use App\Entity\Site;
use App\Entity\Sortie;
use App\Form\SortieSearchType;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

//#[Route('/sorties/display/filter')]
class SortiesDisplayFilterController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function index(SortieRepository $sortieRepository, Request $request): Response
    {

        $sorties = $sortieRepository->getAllSortiesWithUsers();



        $sortie = new Sortie();
        $user = $this->getUser();
        if ($user){
            $idUser= $this->getUser()->getId();
        }

        $form = $this->createForm(SortieSearchType::class, $sortie);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $idSite = $form->get('site')->getData()->getId();
            $nomContient = $form->get('nomSortieContient')->getData();
            $dateDebut = $form->get('dateDebutSorties')->getData();
            $dateFin = $form->get('dateFinSorties')->getData();
            $estOrganisateur =$form->get('organisateurOuPas')->getData();
            $estInscrit =$form->get('inscritOuPas')->getData();
            $nEstPasInscrit =$form->get('nonInscritOuPas')->getData();
            $estPassee =$form->get('passeesOuPas')->getData();

            return $this->render('sortie/index.html.twig', [
                'sortie' => $sortieRepository->filterSorties($user,$idSite,$nomContient,$dateDebut,$dateFin,$estOrganisateur, $estInscrit),
                'form' => $form,
            ]);
        }

            return $this->render('sortie/index.html.twig', [
                'sortie' => $sortieRepository->getAllSortiesWithUsers($user),
                'form' => $form,
            ]);
        }


    }
