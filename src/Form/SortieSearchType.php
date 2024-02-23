<?php

namespace App\Form;

use App\Entity\Participant;
use App\Entity\Site;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'nomSite',
            ])
            ->add('nomSortieContient', SearchType::class,[
                'mapped'=>false,
                'required'=>false,
                'label'=>"Le nom de la sortie contient:",
            ])
            ->add('dateDebutSorties', DateType::class,[
                'mapped'=>false,
                'required'=>false,
                'label'=>"Entre:",
            ])
            ->add('dateFinSorties', DateType::class,[
                'mapped'=>false,
                'required'=>false,
                'label'=>"et:",
            ])
            ->add('organisateurOuPas', CheckboxType::class,[
                'mapped'=>false,
                'required'=>false,

                'label'=>"Sorties dont je suis l'organisateur/trice"
            ])
            ->add('inscritOuPas', CheckboxType::class,[
                'mapped'=>false,
                'required'=>false,

                'label'=>"Sorties auxquelles je suis instrit/e"
            ])
            ->add('nonInscritOuPas', CheckboxType::class,[
                'mapped'=>false,
                'required'=>false,

                'label'=>"Sorties auxquelles je ne suis pas instrit/e"
            ])
            ->add('passeesOuPas', CheckboxType::class,[
                'mapped'=>false,
                'required'=>false,

                'label'=>"Sorties passées"
            ])
            ->add('submit', SubmitType::class,[
                'label'=>"Rechercher"

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
