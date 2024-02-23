<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\Sortie;
use App\Repository\SiteRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomSortie', TextType::class,[
                'label' => 'Nom de la sortie :',
                'required' => false,
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Date et heure de la sortie :',
                'required' => false,
            ])
            ->add('dateLimiteInscription', DateTimeType::class, [
                'label' => 'Date limite d\'inscription :',
                'required' => false,
            ])
            ->add('nbInscriptionMax', IntegerType ::class,[
                'label' => 'Nombre de place :',
                'required' => false,
            ])
            ->add('duree', IntegerType::class,[
                'label' => 'Durée :',
                'required' => false,
            ])
            ->add('infosSortie', TextareaType::class,[
                'label' => 'Description et infos :',
                'required' => false,
            ])
            ->add('motif', TextareaType::class,[
                'label' => 'Motif :',
                'required' => false,
            ])

            ->add('site', EntityType::class, [
                'label' => 'Ville Organisatrice :',
                'class' => Site::class,
                'choice_label' => 'nomSite',
                'required' => false,
                'query_builder' =>function (SiteRepository $siteRepository) {
                    return $siteRepository ->createQueryBuilder('s');
                }
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
