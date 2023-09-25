<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Inter;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class InterFormType extends AbstractType
{
    private $userRepository; 

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Date', DateType::class, [
                'required' => true, 
                'widget' => "single_text",
            ])
            ->add('Anomalie')
            ->add('Salaire', IntegerType::class, [
            ])
            ->add('technicien', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom',
                'query_builder' => function(UserRepository $userRepository) {
                    return $userRepository->createQueryBuilder('r')
                        ->andWhere('r.statut = 1');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inter::class,
        ]);
    }
}
