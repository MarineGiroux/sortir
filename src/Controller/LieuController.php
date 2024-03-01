<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use App\Service\AddressApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/lieu', name: 'app_lieu')]
class LieuController extends AbstractController
{
    #[Route('/create', name: '_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager1, AddressApiService $addressApiService): Response
    {
        $idSortie = @$request->get('idSortie');
        $lieu = new Lieu();
        $form1 = $this->createForm(LieuType::class, $lieu);
        $form1->remove('latitude');
        $form1->remove('longitude');
        $form1->handleRequest($request);

        if ($form1->isSubmitted() && $form1->isValid()) {
            $reponse = $addressApiService->geocodeAddress($lieu->getRue() . " " . $lieu->getVille()->getNomVille());
//dd($reponse);
//dd($reponse["features"][0]["geometry"]["coordinates"][0] );
            $lieu->setLongitude($reponse["features"][0]["geometry"]["coordinates"][0]);
            $lieu->setLatitude($reponse["features"][0]["geometry"]["coordinates"][1]);
            $lieu->setLongitude($reponse["features"][0]["geometry"]["coordinates"][0]);
            $lieu->setLatitude($reponse["features"][0]["geometry"]["coordinates"][1]);
            $entityManager1->persist($lieu);
            $entityManager1->flush();

            if ($idSortie){
                return $this->redirectToRoute('app_sortie_update', ['id' => $idSortie]);
            } else {
                return $this->redirectToRoute('app_sortie_create');
            }
        }

        return $this->render('sortie/createLieu.html.twig', [
            'lieuform' => $form1,
            'idSortie' => $idSortie,
        ]);
    }

}
