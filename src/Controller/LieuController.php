<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/lieu', name: 'app_lieu')]
class LieuController extends AbstractController
{
    #[Route('/create', name: '_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager1): Response
    {
        $lieu = new Lieu();
        $form1 = $this->createForm(LieuType::class, $lieu);
        $form1->handleRequest($request);

        if ($form1->isSubmitted() && $form1->isValid()) {


            $entityManager1->persist($lieu);
            $entityManager1->flush();

            return $this->redirectToRoute('app_sortie_create');
        }

        return $this->render('sortie/createLieu.html.twig', [
            'lieuform' => $form1,

        ]);
    }

}
