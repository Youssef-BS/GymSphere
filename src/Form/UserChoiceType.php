<?php

namespace App\Form;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class UserChoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nomProduit')
        ->add('prixProduit')
        ->add('imageFile', FileType::class, [
            'label' => 'Image', 
            'mapped' => false, 
            'required' => false,
            'attr' => [
                'accept' => 'image/*', 
                'onchange' => 'document.getElementById("image-file-name").textContent = this.files[0].name;',
            ],
        ])
        ->add('quantiteProduit')
        ->add('Confirm',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
    
}

