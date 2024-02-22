<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\LieuType;
use App\Form\SiteType;
use App\Form\SortieType;
use App\Form\VilleType;
use App\Repository\EtatRepository;
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
    public function create(Request $request, EntityManagerInterface $entityManager, EtatRepository $etatRepository, Security $security, UserRepository $userRepository): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        $userRepository = $security->getUser();
        $sortie->setOrganisateur($userRepository);

            if ($form->isSubmitted() && $form->isValid()) {

                if( $request->request->get('submitAction') == 'creer'){
                    $sortie->setEtat($etatRepository->find(1));
                } else if ($request->request->get('submitAction') == 'ouvrir'){
                    $sortie->setEtat($etatRepository->find(2));
                }

                $entityManager->persist($sortie);
                $entityManager->flush();

                return $this->redirectToRoute('app_home');
            }

        return $this->render('sortie/create.html.twig', [
            'sortieform' => $form
        ]);
    }

    #[Route('/update/{id}', name: '_update', requirements: ['id' => '\d+'])]
    public function update(Request $request, EntityManagerInterface $entityManager,Sortie $sortie, EtatRepository $etatRepository, Security $security): Response
    {
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        $userRepository = $security->getUser();
        $sortie->setOrganisateur($userRepository);

        if ($form->isSubmitted() && $form->isValid()) {

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
            'sortieform' => $form
        ]);
    }

    #[Route('/delete/{id}', name: '_delete', requirements: ['id' => '\d+'])]
    public function delete(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
            $request->request->get('submitAction') == 'delete';
            $entityManager->remove($sortie);
            $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }


}
