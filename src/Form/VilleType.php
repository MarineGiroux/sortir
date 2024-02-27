<?php

namespace App\Form;

use App\Entity\Ville;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VilleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomVille', TextType::class,[
                'label' => 'Ville :',
                'label_attr' => ['class' => 'required-label'],
                'required' => false,
            ])
            ->add('codePostal', IntegerType::class,[
                'label' => 'Code Postal :',
                'label_attr' => ['class' => 'required-label'],
                'required' => false,
            ])

            ->add('nomVilleContient', SearchType::class,[
                'mapped'=>false,
                'required'=>false,
                'label'=>"Le nom de la ville contient:",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ville::class,
        ]);
    }
}
