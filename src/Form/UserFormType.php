<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserFormType extends AbstractType 
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            $builder
                ->add('email', EmailType::class, [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'required' => false,
                    'label' => 'Adresse mail :'
                ])
                ->add('username', TextType::class, [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Merci d\'entrer votre nom d\'utilisateur',
                        ]),
                        new Length([
                            'min' => 5,
                            'minMessage' => 'Votre nom d\'utilisateur doit comporter au moins {{ limit }} caractères',
                            'max' => 4096,
                        ]),
                    ],
                    'required' => false,
                    'label' => 'Nom d\'utilisateur :'
                ])
                ->add('plainPassword', PasswordType::class, [
                    'mapped' => false,
                    'label' => 'Nouveau mot de passe :',
                    'attr' => ['autocomplete' => 'new-password',
                    'class' => 'form-control',
                    ],
                    'constraints' => [
                        new Length([
                            'min' => 8,
                            'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                            'max' => 4096,
                        ]),
                    ],
                    'required' => false,
                ])
                ->add('avatar', FileType::class, [
                    'multiple' => false,
                    'required' => false,
                    'mapped' => false,
                    'label' => "Avatar :",
                    'constraints' => [
                        new File([
                            'maxSize' => '1Mi',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                            ],
                        ])
                    ],
                    'attr' => ['placeholder' => "Choisir l'image"]
                ])
                ->add('submit', SubmitType::class, [
                    'label' => 'Valider',
                    'attr' => ['class' => 'btn btn-secondary'],
                ]);
            ;
        }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}