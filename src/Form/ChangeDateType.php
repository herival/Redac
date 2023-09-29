<?php

namespace App\Form;

use App\Entity\Parametres;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeDateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('valeur', ChoiceType::class, [
                'choices' => [
                    'Janvier' =>'Janvier',
                    'Fevrier' =>'Fevrier',
                    'Mars'=>'Mars',
                    'Avril'=>'Avril',
                    'Mai'=>'Mai',
                    'Juin'=>'Juin',
                    'Juillet'=>'Juillet',
                    'Aout'=>'Aout',
                    'Septembre'=>'Septembre',
                    'Octobre'=>'Octobre',
                    'Novembre'=>'Novembre',
                    'Decembre'=>'Decembre'
                ],
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Parametres::class,
        ]);
    }
}
