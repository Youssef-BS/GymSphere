<?php

namespace App\Form;

use App\Entity\Event;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType ;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,['empty_data' => ''])
            ->add('description', TextareaType::class,['empty_data' => ''])
            ->add('duree', TextType::class,['empty_data' => ''])
            ->add('type', TextType::class,['empty_data' => ''])
            ->add('date_debut', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'empty_data' => '',
                'required' => true,  // Setting the field as required
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'La date ldebut est requise.',
                    ]),
                    new Assert\NotNull([
                        'message' => 'La date debut ne peut pas être nulle.',
                    ]),
                ],
                'data' => new \DateTime(),
            ])
            ->add('nb_participants')
            ->add('nb_max')
            ->add('localisation', TextType::class,['empty_data' => ''])
            ->add('status', TextType::class,['empty_data' => ''])
            ->add('Program', EntityType::class, [
                'class' => 'App\Entity\Program', 
                'choice_label' => 'nom',
                'placeholder' => 'Select a program', 
                'required' => true, 

            ])
            ->add('Save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
