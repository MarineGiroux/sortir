<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rue', TextType::class,[
                'label' => 'Rue :',
                'required' => false,
            ])
            ->add('nomLieu', TextType::class,[
                'label' => 'Lieu :',
                'label_attr' => ['class' => 'required-label'],
                'required' => false,
            ])
            ->add('latitude', IntegerType::class,[
                'label' => 'Latitude :',
                'required' => false,
            ])
            ->add('longitude', IntegerType::class,[
                'label' => 'Longitude :',
                'required' => false,
            ])
            ->add('ville', EntityType::class,[
                'class' => Ville::class,
                'choice_label' => 'nomVille',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
