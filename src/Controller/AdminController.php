<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/users', name: 'app_dashboard_users')]
    public function listUsers(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/listUsers.html.twig', [
            'users' => $users
        ]);
    }


    #[Route('/admin/sorties', name: 'app_dashboard-sortie')]
    public function listeSorties(SortieRepository $sortieRepository): Response
    {

        $sorties = $sortieRepository->findAll();
        return $this->render('admin/listesortie.html.twig', [
            'sorties' => $sorties
        ]);
//            return $this->redirectToRoute('app_sorties_display_filter_show');
    }


    #[Route('/admin/sorties/detail/{id}', name: 'app_dashboard-sortie_detail', requirements: ['id' => '\d+'])]
    public function detailSortie(Sortie $sortie): Response
    {
        return $this->render('admin/detailSortie.html.twig', [
            'sortie' => $sortie,
        ]);
    }


    #[Route('/admin/sorties/annuler/{id}', name: 'app_dashboard-sortie_annuler', requirements: ['id' => '\d+'])]
    public function annulerSortie(Request $request, EntityManagerInterface $entityManager, Sortie $sortie, EtatRepository $etatRepository, Security $security): Response
    {
        $form = $this->createForm(SortieType::class, $sortie);

        $form->remove('nomSortie');
        $form->remove('dateHeureDebut');
        $form->remove('dateLimiteInscription');
        $form->remove('nbInscriptionMax');
        $form->remove('duree');
        $form->remove('infosSortie');
        $form->remove('lieu');
        $form->remove('site');

        $form->handleRequest($request);

        $userRepository = $security->getUser();
        $sortie->setOrganisateur($userRepository);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($request->request->get('submitAction') == 'annuler') {
                $sortie->setEtat($etatRepository->find(6));


                $entityManager->persist($sortie);
                $entityManager->flush();

                return $this->redirectToRoute('app_dashboard-sortie');
            }


        }
        return $this->render('admin/cancelSortie.html.twig', [
            'sortieform' => $form, 'sortie' => $sortie
        ]);
    }


    #[Route('/admin/sorties/reactiver/{id}', name: 'app_dashboard-sortie_reactiver', requirements: ['id' => '\d+'])]
    public function reactiverSortie(Request $request, EntityManagerInterface $entityManager, Sortie $sortie, EtatRepository $etatRepository, Security $security): Response
    {
        $form = $this->createForm(SortieType::class, $sortie);

        $form->remove('nomSortie');
        $form->remove('dateHeureDebut');
        $form->remove('dateLimiteInscription');
        $form->remove('nbInscriptionMax');
        $form->remove('duree');
        $form->remove('infosSortie');
        $form->remove('lieu');
        $form->remove('site');

        $form->handleRequest($request);

        $userRepository = $security->getUser();
        $sortie->setOrganisateur($userRepository);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($request->request->get('submitAction') == 'annuler') {
                $sortie->setEtat($etatRepository->find(2));


                $entityManager->persist($sortie);
                $entityManager->flush();

                return $this->redirectToRoute('app_dashboard-sortie');
            }


        }
        return $this->render('admin/reactiverSortie.html.twig', [
            'sortieform' => $form, 'sortie' => $sortie
        ]);
    }





}