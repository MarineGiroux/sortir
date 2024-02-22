<?php

namespace App\Form;

use App\Entity\Lieu;
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
