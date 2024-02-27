<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\LieuType;
use App\Form\SortieType;
use App\Form\VilleType;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
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
    public function create(Request $request, EntityManagerInterface $entityManager, EntityManagerInterface $entityManager1, EntityManagerInterface $entityManager2,EtatRepository $etatRepository, Security $security): Response
    {
        $sortie = new Sortie();
        $site = $this->getUser()->getSite();
        $sortie->setSite($site);

        $form = $this->createForm(SortieType::class, $sortie, ['site' => $site]);
        $form->remove('site');
        $form->remove('motif');


        $form->handleRequest($request);

        $userRepository = $security->getUser();
        $sortie->setOrganisateur($userRepository);


        $lieu = new Lieu();
        $form1 = $this->createForm(LieuType::class, $lieu);
        $form1->handleRequest($request);

        $ville = new Ville();
        $form2 = $this->createForm(VilleType::class, $ville);
        $form2->remove('nomVilleContient');

        $form2->handleRequest($request);




            if ($form->isSubmitted() && $form->isValid()
                && $form1->isSubmitted() && $form1->isValid()
                && $form2->isSubmitted() && $form2->isValid()
            ) {

                if( $request->request->get('submitAction') == 'creer'){
                    $sortie->setEtat($etatRepository->find(1));
                } else if ($request->request->get('submitAction') == 'ouvrir'){
                    $sortie->setEtat($etatRepository->find(2));
                }

                $sortie->setLieu($lieu);
                $lieu->setVille($ville);
                $entityManager->persist($sortie);
                $entityManager1->persist($lieu);
                $entityManager2->persist($ville);
                $entityManager->flush();
                $entityManager1->flush();
                $entityManager2->flush();

                return $this->redirectToRoute('app_home');
            }

        return $this->render('sortie/create.html.twig', [
            'sortieform' => $form ,'lieuform' => $form1, 'villeform' => $form2,
        ]);
    }

    #[Route('/update/{id}', name: '_update', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function update(Request $request, EntityManagerInterface $entityManager, EntityManagerInterface $entityManager1, EntityManagerInterface $entityManager2 ,Sortie $sortie, LieuRepository $lieuRepository,VilleRepository $villeRepository , EtatRepository $etatRepository, Security $security): Response
    {
        $site = $this->getUser()->getSite();
        $sortie->setSite($site);
        $form = $this->createForm(SortieType::class, $sortie);
        $form->remove('motif');
        $form->remove('site');
        $form->handleRequest($request);

        $userRepository = $security->getUser();
        $sortie->setOrganisateur($userRepository);

        $lieu = $lieuRepository->findOneBy(['id' => $sortie->getLieu()->getId()]);

        $ville = $lieu->getVille();

        $form1 = $this->createForm(LieuType::class, $lieu);
        $form1->handleRequest($request);

        $form2 = $this->createForm(VilleType::class, $ville);
        $form2->remove('nomVilleContient');
        $form2->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()
            && $form1->isSubmitted() && $form1->isValid()
            && $form2->isSubmitted() && $form2->isValid()
        ) {
            if( $request->request->get('submitAction') == 'creer'){
                $sortie->setEtat($etatRepository->find(1));
            } else if ($request->request->get('submitAction') == 'ouvrir'){
                $sortie->setEtat($etatRepository->find(2));
            }
            $sortie->setLieu($lieu);
            $lieu->setVille($ville);

            $entityManager->persist($sortie);
            $entityManager1->persist($lieu);
            $entityManager2->persist($ville);
            $entityManager->flush();
            $entityManager1->flush();
            $entityManager2->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('sortie/update.html.twig', [
            'sortieform' => $form ,'lieuform' => $form1, 'villeform' => $form2, 'sortie' => $sortie
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
