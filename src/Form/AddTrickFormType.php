<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;

class AddTrickFormType extends AbstractType
{
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
                'label' => 'Titre :',
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description :',
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('categories', EntityType::class, [
                'label' => 'Catégorie :',
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('picture', FileType::class, [
                'label' => 'Images :',
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
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-secondary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => null,
        ]);
    }
}