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


        $sortie = new Sortie();

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

            return $this->render('sortie/list.html.twig', [
                'sortie' => $sortieRepository->filterSorties($idSite,$nomContient,$dateDebut,$dateFin),
                'form' => $form,
            ]);
        }

        return $this->render('sortie/index.html.twig', [
            'sortie' => $sortieRepository->findAll(),
            'form' => $form,
        ]);
    }



    #[Route('/new', name: 'app_sorties_display_filter_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('app_sorties_display_filter_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sorties_display_filter/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sorties_display_filter_show', methods: ['GET'])]
    public function show(Sortie $sortie): Response
    {
        return $this->render('sorties_display_filter/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sorties_display_filter_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sorties_display_filter_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sorties_display_filter/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sorties_display_filter_delete', methods: ['POST'])]
    public function delete(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sortie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sortie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sorties_display_filter_index', [], Response::HTTP_SEE_OTHER);
    }
}
