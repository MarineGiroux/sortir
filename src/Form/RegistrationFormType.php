<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\User;
use App\Repository\SiteRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'required' => false,
                'label_attr' => ['class' => 'required-label'],
            ])
            ->add('prenom', TextType::class, [
                'label'=>'Prénom',
                'required' => false,
                'label_attr' => ['class' => 'required-label'],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'label_attr' => ['class' => 'required-label'],
            ])
            ->add('email', TextType::class,[
                'required' => false,
                'label_attr' => ['class' => 'required-label'],
            ])
            ->add('telephone', TelType::class,[
                'label'=>'Numéro de téléphone',
                'required' => false,
            ])
            ->add('photo', HiddenType::class)
            ->add('photo_file', FileType::class, [
                'label' => 'Photo du profil',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => "format acceptés : jpeg, jpg et png seulement",
                        'maxSizeMessage' => "Fichier trop lourd"
                    ])
                ]
            ])


            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'nomSite',
                'label_attr' => ['class' => 'required-label'],
                'required' => false,
                'query_builder' =>function (SiteRepository $siteRepository) {
//                    return $siteRepository->createQueryBuilder('s')->addGroupBy('s.nomSite');
            return $siteRepository ->createQueryBuilder('s');
            }
            ])


            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => false,
                'options' => [
                    'attr' => [
                        'autocomplete' => 'Password',
                    ],
                ],
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Merci d\'entrer un mot de passe',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'label_attr' => ['class' => 'required-label'],
                    'label' => 'Votre mot de passe',
                ],
                'second_options' => [
                    'label_attr' => ['class' => 'required-label'],
                    'label' => 'Confirmation du mot de passe',
                ],
                'invalid_message' => 'les mots de passe ne correspondent pas',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
