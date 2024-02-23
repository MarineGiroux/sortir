<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{
    #[Route('/user/profile', name: 'app_user_profile')]
    public function afficherProfil(): Response
    {
        return $this->render('user/profil.html.twig');
    }

    #[Route ('/user/profile/update', name: 'app_user_profile_update')]
    
    public function updateProfile(Request $request, EntityManagerInterface $entityManager): Response
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


            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Le profil a été modifié');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('user/updateProfile.html.twig', [
            'form' => $form
        ]);
    }


    #[Route('/user/delete/{id}', name:'app_user_delete', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteUser(User $user, EntityManagerInterface $em): Response
    {

//        $user = $this->getUser();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_user_list');

    }

    #[Route('user/list', name:'app_user_list')]
    #[IsGranted('ROLE_ADMIN')]
    public function listUsers(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('user/listUsers.html.twig', [
            'users' => $users
        ]);
    }

}
