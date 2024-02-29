<?php

namespace App\Controller;

use App\Form\SortieSearchType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/user', name: 'app_user')]
class UserController extends AbstractController
{
    #[Route('/profile', name: '_profile')]
    public function afficherProfil(SortieRepository $sortieRepository, Request $request): Response
    {
        $user = $this->getUser();
        $sorties = $sortieRepository->findAll();
        $form = $this->createForm(SortieSearchType::class, $sorties,
            ['nomSiteUser' => $user->getSite()->getNomSite(),
            ]);
        $form->handleRequest($request);

        return $this->render('user/profil.html.twig', [
            'sortie' => $sortieRepository->findAll(),
            'form' => $form,
        ]);
    }

    #[Route ('/profile/update', name: '_profile_update')]
    
    public function updateProfile(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {

        $user = $this->getUser();

        $form = $this->createForm(RegistrationFormType::class, $user);

        //ne pas afficher pseudo (car pas droit de le modifier) ou l'afficher en lecture seule (decommentant la suite)
            $form->remove('pseudo');
//            $form->add('pseudo', TextType::class,[
//                'disabled' =>true,
//                'data' =>$user->getPseudo(),
//            ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $request->request->get('submitAction')=='enregistrer';

            if ($form->get('photo_file')->getData() instanceof UploadedFile) {
                $avatarFile = $form->get('photo_file')->getData();
                $fileName = $slugger->slug($user->getId()) . '-' . uniqid() . '.' . $avatarFile->guessExtension();
                $avatarFile->move($this->getParameter('photo_dir'), $fileName);
                $user->setPhoto($fileName);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Le profil a été modifié');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('user/updateProfile.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/delete/{id}', name:'_delete', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteUser(User $user, EntityManagerInterface $em): Response
    {

//        $user = $this->getUser();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_user_list');

    }

    #[Route('/list', name:'_list')]
    #[IsGranted('ROLE_ADMIN')]
    public function listUsers(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/listUsers.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/profile/show/{id}', name: '_profile_show')]
    public function showUserProfile(User $user, Request $request, SessionInterface $session): Response
    {
        $referer = $request->headers->get('referer');
        $session->set('previous_page_url', $referer);

        return $this->render('user/profilInscrit.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/inactif/{id}',  name:'_desactivate', requirements: ['id' => '\d+'])]
    public function desactivateUser(User $user, EntityManagerInterface $em): Response
    {
        $user->setIsActif(false);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('app_user_list');

    }

    #[Route('/actif/{id}',  name:'_reactivate', requirements: ['id' => '\d+'])]
    public function reactivateUser(User $user,  EntityManagerInterface $em): Response
    {

        $user->setIsActif(true);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('app_user_list');

    }

}
