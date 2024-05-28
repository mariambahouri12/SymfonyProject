<?php

namespace App\Form;

use App\Entity\USER;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('mail')
            ->add('age')
            ->add('height')
            ->add('weight')
            ->add('gender')
            ->add('editer', SubmitType::class, [
                'label' => 'next',
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => USER::class,
        ]);
    }
}
