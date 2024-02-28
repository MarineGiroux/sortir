<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/villes', name: 'app_villes')]
class VilleController extends AbstractController
{
    #[Route('/', name: '_list')]
    public function filterVilles(VilleRepository $villeRepository, Request $request): Response
    {

        $ville = new Ville();

        $form = $this->createForm(VilleType::class, $ville);
        $form->remove('nomVille');
        $form->remove('codePostal');

        $form ->handleRequest($request);
        $villes=$villeRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $nomContient = $form->get('nomVilleContient')->getData();

            $villes = $villeRepository->filtreVilleByNom($nomContient);
        }

        return $this->render('ville/gestionVilles.html.twig', [
            'villes' => $villes,
            'form' => $form
        ]);
    }


    #[Route('/create', name: '_create', methods: ['GET', 'POST'])]
    public function createVille(Request $request, EntityManagerInterface $em): response
    {

        $ville = new Ville();

        $form = $this->createForm(VilleType::class, $ville);
        $form->remove('nomVilleContient');
        $form -> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($ville);
            $em->flush();

            $this->addFlash('success', 'La ville est créée');
            return $this->redirectToRoute('app_villes_list');
        }

        return $this->render('ville/createVille.html.twig',[
            'form' =>$form
        ]);
    }





    #[Route('/update/{id}', name: '_update',requirements:['id' =>'\d+'])]
    public function updateVille(Ville $ville,  Request $request, EntityManagerInterface $em): response
    {


        $form = $this->createForm(VilleType::class, $ville);
        $form->remove('nomVilleContient');

        $form -> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($ville);
            $em->flush();

            $this->addFlash('success', 'La ville a été modifiée');
            return $this->redirectToRoute('app_villes_list');
        }

        return $this->render('ville/updateVille.html.twig',[
            'form' =>$form
        ]);
    }



    #[Route('/delete/{id}', name: '_delete', requirements:['id' =>'\d+'])]
    public function deleteville(Ville $ville,EntityManagerInterface $em): Response
    {
        $em->remove($ville);
        $em->flush();


        return $this->redirectToRoute('app_villes_list');
    }

    #[Route('/create/sortie', name: '_create_sortie', methods: ['GET', 'POST'])]
    public function createVilleSortie(Request $request, EntityManagerInterface $em): response
    {

        $ville = new Ville();

        $form = $this->createForm(VilleType::class, $ville);
        $form->remove('nomVilleContient');
        $form -> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($ville);
            $em->flush();

            $this->addFlash('success', 'La ville est créée');
            return $this->redirectToRoute('app_lieu_create');
        }

        return $this->render('sortie/createVille.html.twig',[
            'villeform' =>$form
        ]);
    }





}
