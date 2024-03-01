<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\LieuType;
use App\Form\SortieType;
use App\Form\VilleType;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/sortie', name: 'app_sortie')]
class SortieController extends AbstractController
{

    #[Route('/detail/{id}', name: '_detail', requirements: ['id' => '\d+'])]
    public function detail(Sortie $sortie): Response
    {
        return $this->render('sortie/detail.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    #[Route('/create', name: '_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, EtatRepository $etatRepository, Security $security): Response
    {
        // Crée une nouvelle instance de l'entité Sortie
        $sortie = new Sortie();

        // Récupère le site associé à l'utilisateur actuellement connecté
        $site = $this->getUser()->getSite();
        $sortie->setSite($site);

        // Créer un formulaire basé sur le type SortieType avec le site comme option
        $form = $this->createForm(SortieType::class, $sortie, ['site' => $site]);

        // Supprime les champs 'site' et 'motif' du formulaire
        $form->remove('site');
        $form->remove('motif');

        // Traite la requête HTTP pour remplir le formulaire avec les données soumises
        $form->handleRequest($request);

        // Récupèration de l'utilisateur connecté
        $userRepository = $security->getUser();
        $sortie->setOrganisateur($userRepository);

        // Vérifie si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Détermine l'état de la sortie suivant le bouton sélectionné
            if ($request->request->get('submitAction') == 'creer') {
                $sortie->setEtat($etatRepository->find(1));
            } else if ($request->request->get('submitAction') == 'ouvrir') {
                $sortie->setEtat($etatRepository->find(2));
            }

            // Persiste l'entité dans la base de données
            $entityManager->persist($sortie);
            // Applique les changements à la base de données
            $entityManager->flush();

            // Redirige vers la page d'accueil
            return $this->redirectToRoute('app_home');
        }
        // Rend le template 'sortie/create.html.twig' avec le formulaire en cours
        return $this->render('sortie/create.html.twig', [
            'sortieform' => $form,
        ]);
    }

    #[Route('/update/{id}', name: '_update', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function update(Request $request, EntityManagerInterface $entityManager,Sortie $sortie, EtatRepository $etatRepository, Security $security): Response
    {
        $site = $this->getUser()->getSite();
        $sortie->setSite($site);
        $form = $this->createForm(SortieType::class, $sortie);
        $form->remove('motif');
        $form->remove('site');
        $form->handleRequest($request);

        $userRepository = $security->getUser();
        $sortie->setOrganisateur($userRepository);


        if ($form->isSubmitted() && $form->isValid()
        ) {
            if( $request->request->get('submitAction') == 'creer'){
                $sortie->setEtat($etatRepository->find(1));
            } else if ($request->request->get('submitAction') == 'ouvrir'){
                $sortie->setEtat($etatRepository->find(2));
            }

            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('sortie/update.html.twig', [
            'sortieform' => $form ,'sortie' => $sortie
        ]);
    }

    #[Route('/delete/{id}', name: '_delete', requirements: ['id' => '\d+'])]
    public function delete(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
            $entityManager->remove($sortie);
            $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }

    #[Route('/annuler/{id}', name: '_annuler', requirements: ['id' => '\d+'])]
    public function annuler(Request $request, EntityManagerInterface $entityManager,Sortie $sortie, EtatRepository $etatRepository, Security $security): Response
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

                return $this->redirectToRoute('app_home');
            }


        }
        return $this->render('sortie/cancel.html.twig', [
            'sortieform' => $form, 'sortie' => $sortie
        ]);
    }



}
