<?php

namespace App\Form;

use App\Entity\Program;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class,['empty_data' => ''])
        ->add('description', TextareaType::class,['empty_data' => ''])
        ->add('duree', TextType::class,['empty_data' => ''])
        ->add('registration_deadline', DateType::class, [
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'empty_data' => '',
            'required' => true,  // Setting the field as required
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'La date limite d\'inscription est requise.',
                ]),
                new Assert\NotNull([
                    'message' => 'La date limite d\'inscription ne peut pas être nulle.',
                ]),
            ],
            'data' => new \DateTime(),
        ])
        ->add('prix', MoneyType::class,['empty_data' => '','currency' => 'TND',])
        ->add('imgsrc', FileType::class, [
            'label' => 'Image (JPEG or PNG file)',
            'required' => false,
            'mapped' => false,
            'constraints' => [
                new Assert\File([
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid JPEG or PNG image.',
                ]),
            ],
        ])
        ->add('Save', SubmitType::class)
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Program::class,
        ]);
    }
    
}
