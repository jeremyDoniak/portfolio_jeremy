<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class, [
                'required' => true,
                'attr' => [
                    'maxLength' => 100
                ]
            ])
            ->add('nom', TextType::class, [
                'required' => true,
                'attr' => [
                    'maxLength' => 100
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'attr' => [
                    'maxLength' => 100
                ]
            ])
            ->add('message', TextareaType::class, [
                'required' => true,
                'attr' => [
                    'minLength' => 50,
                    'maxLength' => 1000
                ],
                'help' => '1000 caractères maximum'
            ])
            ->add('fichier', FileType::class, [
                'required' => false,
                'help' => 'PNG, JPEG, WEBP ou PDF - 4 Mo maximum',
                'constraints' => [
                    new File([
                        'maxSize' => '4M',
                        'maxSizeMessage' => 'Le fichier est trop volumineux ({{ size }} {{ suffix }}). La taille maximum autorisée est de {{ limit }} {{ suffix }}',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                            'image/webp',
                            'application/pdf'
                        ],
                        'mimeTypesMessage' => 'Le format de fichier est invalide ({{ type }}). Les types autorisés sont : {{ types }}.'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
