<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\EtatType;
use App\Form\LieuType;
use App\Form\SiteType;
use App\Form\SortieType;
use App\Form\VilleType;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function create(Request $request, EntityManagerInterface $entityManager, EtatRepository $etatRepository, ParticipantRepository $participantRepository): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {


            $sortie->setEtat($etatRepository->find(1));
            $sortie->setOrganisateur($participantRepository->find(1));

            $entityManager->persist($sortie);
            $entityManager->flush();


            return $this->redirectToRoute('app_home');
        }

        return $this->render('sortie/create.html.twig', [
            'sortieform' => $form,
        ]);
    }

    #[Route('/update/{id}', name: '_update', requirements: ['id' => '\d+'])]
    public function update(Request $request, Sortie $sortie, Lieu $lieu, Ville $ville, Site $site, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        $form1=$this->createForm(LieuType::class,$lieu);
        $form1->handleRequest($request);

        $form2=$this->createForm(VilleType::class,$ville);
        $form2->handleRequest($request);

        $form3=$this->createForm(SiteType::class,$site);
        $form3->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid() &&
            $form1->isSubmitted() && $form1->isValid() &&
            $form2->isSubmitted() && $form2->isValid() &&
            $form3->isSubmitted() && $form3->isValid()) {

            $entityManager->persist($sortie);
            $entityManager->persist($lieu);
            $entityManager->persist($ville);
            $entityManager->persist($site);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('sortie/update.html.twig', [
            'sortieform' => $form,'lieuform' => $form1, 'villeform' => $form2, 'siteform' => $form3,
        ]);
    }

    #[Route('/delete/{id}', name: '_delete', requirements: ['id' => '\d+'])]
    public function delete(Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
            $entityManager->remove($sortie);
            $entityManager->flush();

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }


}
