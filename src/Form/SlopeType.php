<?php

namespace App\Form;

use App\Entity\Slope;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SlopeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('difficulty')
            ->add('first_hour')
            ->add('last_hour')
            ->add('exception')
            ->add('exception_message')
            ->add('Peak_Hour')
            ->add('snow_quality')
            ->add('station')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Slope::class,
        ]);
    }
}
