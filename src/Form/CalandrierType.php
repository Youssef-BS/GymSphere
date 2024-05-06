<?php

namespace App\Form;

use App\Entity\Calandrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalandrierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
             ->add('dateActivite')
            ->add('typeactivite')
            ->add('description')
            ->add('heurefermuture')
            ->add('idgym')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Calandrier::class,
        ]);
    }
}
