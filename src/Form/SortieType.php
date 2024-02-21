<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Repository\LieuRepository;
use App\Repository\SiteRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomSortie', TextType::class,[
                'label' => 'Nom de la sortie :'
            ])
            ->add('dateHeureDebut', DateType::class, [
                'label' => 'Date et heure de la sortie :',
            ])
            ->add('dateLimiteInscription', DateType::class, [
                'label' => 'Date limite d\'inscription :',
            ])
            ->add('nbInscriptionMax', IntegerType ::class,[
                'label' => 'Nombre de place :'
            ])
            ->add('duree', IntegerType::class,[
                'label' => 'Durée :'
            ])
            ->add('infosSortie', TextareaType::class,[
                'label' => 'Description et infos :',
            ])
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'nomSite',
                'query_builder' =>function (SiteRepository $siteRepository) {
                    return $siteRepository ->createQueryBuilder('s');
                }
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'nomLieu',
                'query_builder' =>function (LieuRepository $lieuRepository) {
                    return $lieuRepository ->createQueryBuilder('s');
                }
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
