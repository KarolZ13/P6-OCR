<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\VideoType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditTrickFormType extends AbstractType 
{


    /**
     * Short description here.
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', HiddenType::class, [
                'data' => $options['user'],
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre du trick :',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
                'label_attr' => [
                    'class' => 'custom-label-class',
                    'style' => 'font-size: 20px; margin-bottom: 5px',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un titre.',
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description :',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 8,
                ],
                'label_attr' => [
                    'class' => 'custom-label-class',
                    'style' => 'font-size: 20px; margin-bottom: 5px',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une description.',
                    ]),
                ],
            ])
            ->add('categories', EntityType::class, [
                'label' => 'Catégorie :',
                'class' => Category::class,
                'choice_label' => 'name',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
                'label_attr' => [
                    'class' => 'custom-label-class',
                    'style' => 'font-size: 20px; margin-bottom: 5px',
                ],
            ])
            ->add('picture', FileType::class, [
                'label' => 'Ajouter une ou des nouvelles images : ',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'attr' => ['placeholder' => 'Choisir l\'image'],
                'constraints' => [
                    new All(
                        new File([
                            'maxSize' => '100Mi',
                            'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif', 'text/plain'],
                        ]),
                    )
                ],
            ])
            ->add('existingPictureIds', HiddenType::class, [
                'mapped' => false,
            ])
            ->add('video', CollectionType::class, [
                'entry_type' => VideoType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'mapped' => false,
                'required' => false,
                'prototype' => true,
                'label' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-secondary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => null,
            'data_class' => Trick::class,
        ]);
    }
}