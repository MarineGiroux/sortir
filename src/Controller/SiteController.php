<?php

namespace App\Controller;

use App\Entity\Site;
use App\Form\SiteType;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/sites', name: 'app_sites')]
class SiteController extends AbstractController

{


 #[Route('/', name: '')]
    public function filterSites(SiteRepository $siteRepository, Request $request): Response
{
    $site = new Site();

    $form = $this->createForm(SiteType::class, $site);
    $form->remove('nomSite');

    $form -> handleRequest($request);
    $sites = $siteRepository->findAll();

    if ($form->isSubmitted() && $form->isValid()) {
        $nomContient = $form->get('nomSiteContient')->getData();

        $sites = $siteRepository->filtreSiteByNom($nomContient);
    }

//    $sites = $siteRepository->findAll();
    return $this->render('site/gestionSites.html.twig', [
        'sites' => $sites,
        'form' => $form
    ]);
}






    #[Route('/create', name: '_create', methods: ['GET', 'POST'])]
    public function createSite(Request $request, EntityManagerInterface $em): response
    {

        $site = new Site();

        $form = $this->createForm(SiteType::class, $site);
        $form->remove('nomSiteContient');

        $form -> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($site);
            $em->flush();

            $this->addFlash('success', 'Le site est créé');
            return $this->redirectToRoute('app_sites');
        }

        return $this->render('site/createSite.html.twig',[
            'form' =>$form
        ]);
    }





    #[Route('/update/{id}', name: '_update',requirements:['id' =>'\d+'])]
    public function updateSite(Site $site,  Request $request, EntityManagerInterface $em): response
    {


        $form = $this->createForm(SiteType::class, $site);
        $form->remove('nomSiteContient');

        $form -> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($site);
            $em->flush();

            $this->addFlash('success', 'Le site a été modifié');
            return $this->redirectToRoute('app_sites');
        }

        return $this->render('site/updateSite.html.twig',[
            'form' =>$form
        ]);
    }



    #[Route('/delete/{id}', name: '_delete', requirements:['id' =>'\d+'])]
    public function deleteSite(Site $site,EntityManagerInterface $em): response
    {
        $em->remove($site);
        $em->flush();


        return $this->redirectToRoute('app_sites');
    }








}
