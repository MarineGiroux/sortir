<?php

namespace App\Form;

use App\Entity\Etat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Créer' => 'creer',
                    'Ouverte' => 'ouverte',
                    'Clôturée' => 'cloturee',
                    'Activité en cours' => 'encours',
                    'Passée' => 'passee',
                    'Annulée' => 'annulee',
                    ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etat::class,
        ]);
    }
}
