<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\User;
use App\Enum\FuelTypes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('model', null, [
                'required' => true,
                'attr'=> ['maxlength' => 20,],
                'constraints' => [
                    new Length([
                        'max' => 30,
                        'maxMessage' => 'Model input too long',
                    ])
                ]
            ])
            ->add('registration_date',null,[
                'required' => true,
                'widget' => 'single_text',
                'attr' => [
                    'max' => date('Y-m-d'),
                ]
            ])
            // Use the enum for all the possible fuel options we have set up :
            ->add('fuel', EnumType::class,[
                'required' => true,
                'class' => FuelTypes::class,
            ])
            ->add('color', null, [
                'required' => false,
                'attr' => ['maxlength' => 25,],
                'constraints' => [
                    new Length([
                        'max' => 25,
                        'maxMessage' => 'Color input too long',
                    ])
                ]
            ])
            ->add('registration', TextType::class, [
                'required' => true,
                'attr' => ['maxlength' => 9,],
                'constraints' => [
                    // create the registration plate format for France with regex and FIXED length :
                    new Regex([
                        'pattern' => '/^[A-Za-z]{2}-\d{3}-[A-Za-z]{2}$/',
                        'message' => 'The registration date must be in the format AA-000-AA',
                    ]),
                    new Length(9, exactMessage :''),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
            'crsf_protection' => false,
            'error_bubbling' => true, // third parameter switches off default rendering and positioning for erro messages, better UX
        ]);
    }
}
