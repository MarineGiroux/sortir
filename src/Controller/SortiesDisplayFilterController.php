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
        $user = $this->getUser();

        if ($user) {
            $sorties = $sortieRepository->findAll();
            $form = $this->createForm(SortieSearchType::class, $sorties,
                ['nomSiteUser' => $user->getSite()->getNomSite(),
                ]);


        } else {
            $sorties = $sortieRepository->findAll();
            $form = $this->createForm(SortieSearchType::class, $sorties,
                ['nomSiteUser' => 'Utilisateur non connecté',
                ]);
        }

            $form->handleRequest($request);

            if ($form->isSubmitted()) {

                $siteParDefautOuPas = $form->get('siteParDefautOuPas')->getData();
                $site = $form->get('site')->getData();
                $nomContient = $form->get('nomSortieContient')->getData();
                $dateDebut = $form->get('dateDebutSorties')->getData();
                $dateFin = $form->get('dateFinSorties')->getData();
                $estOrganisateur = $form->get('organisateurOuPas')->getData();
                $inscrit = $form->get('inscrit')->getData();
                $nonInscrit = $form->get('nonInscrit')->getData();
                $estPassee = $form->get('passeesOuPas')->getData();


                    return $this->render('sortie/index.html.twig', [
                        'sortie' => $sortieRepository->filterSorties($user, $siteParDefautOuPas, $site, $nomContient, $dateDebut, $dateFin, $estOrganisateur, $inscrit,$nonInscrit,$estPassee),
                        'form' => $form,
                    ]);
            }

            return $this->render('sortie/index.html.twig', [
                'sortie' => $sortieRepository->getAllSortiesWithUsers($user),
                'form' => $form,
            ]);
        }


    }
