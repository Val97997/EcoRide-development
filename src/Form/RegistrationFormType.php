<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\FileValidator;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo*',
                'required' => true,
                'attr' => ['placeholder' => 'The pseudonym that will be displayed',
                'minlength' => 3,
                'maxlength' => 40,
            ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email*',
                'required' => true,
                'attr' => ['placeholder' => 'test@mail.com', 'maxlength' => 25],
            ])
            ->add('phone_nb', TelType::class, [
                'required' => false,
                'attr' => [
                    'maxlength' => 25,
                    'placeholder' => 'Your personal number',
                    'pattern' => '[0-9 ]+'
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[0-9 ]+$/',
                        'message' => 'numbers only'
                    ]),
                ]
            ])
            ->add('first_name', TextType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'John', 'maxlength' => 20,],
            ])
            ->add('last_name', TextType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Doe', 'maxlength' => 20,],
            ])
            ->add('address', TextType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Your address', 'maxlength' => 50],
            ])
            ->add('birth_date', DateType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Your date of birth'],
            ])
            ->add('picture', FileType::class, [
                'data_class' => null,
                'required' => false,
                'attr' => ['accept' => '.jpg', 'placeholder' => 'Choose a profile picture'],
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'extensions' => ['jpg'],
                        'extensionsMessage' => 'Please upload a valid image (max 1024ko) '
                    ])
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'empty_data' => 'true',
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                
                                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Min 8 char long, 1 Maj, 1 number, 1 special char', 'maxlength' => 40,],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('confirmPw',PasswordType::class, [
                'mapped' => false,
                'attr' => ['placeholder' => 're-enter password to confirm', 'maxlength' => 40,],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'crsf_protection' => false,
        ]);
    }
}
