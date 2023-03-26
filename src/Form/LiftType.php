<?php

namespace App\Form;

use App\Entity\Lift;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LiftType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('first_hour')
            ->add('last_hour')
            ->add('exception')
            ->add('exception_message')
            ->add('Peak_Hour')
            ->add('comfort')
            ->add('station')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lift::class,
        ]);
    }
}
