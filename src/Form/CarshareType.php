<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Carshare;
use App\Entity\User;
use App\Enum\CarshareStatus;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CarshareType extends AbstractType
{
    // IMPORTANT : DEFINE the current user using the app, by implementing here the TokenStorageInterface Security component, 
    // allowing to restrict the available selection to ONLY the USER OWNED cars
    private $user;

    public function __construct(TokenStorageInterface $tokenStorageInterface)
    {
        $this->user = $tokenStorageInterface->getToken()->getUser();
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('departure_date', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('arrival_date', null, [
                'widget' => 'single_text',
                'required' => true,
                'constraints' => [
                 
                ]
            ])
            ->add('departure_hour', TimeType::class, [
                'widget' => 'single_text',
                'required' => true,
                ])
            ->add('arrival_hour', null, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('departure_location', null, [
                'required' => true,
                'attr' => [
                    'minlength' => 1,
                    'maxlength' => 40,
                ],
                'constraints' => [
                    new Length(null, 1,50,maxMessage:'Input for arrival location too long')
                ]
            ])
            ->add('arrival_location', null, [
                'required' => true,
                'attr' => [
                    'minlength' => 1,
                    'maxlength' => 40,
                ],
                'constraints' => [
                    new Length(null,1,50,maxMessage:'Input for departure location too long')
                ]
            ])
            ->add('available_seats', IntegerType::class, [
                'required' => true,
                'data' => 0,
                'attr' => ['min'=>'1', 'max' => '50', 'maxlength' => 2,],
                'constraints' => [
                    new GreaterThan(0,null,'Seats must be greater than 0'),
                    new LessThan(100,null, 'Seats too high !'),
                ]
            ])
            ->add('price', IntegerType::class, [
                'required' => true,
                'data' => 0,
                'attr' => ['min' => '0', 'max' => '1000', 'maxlength' => 4],
                'constraints' => [
                    new GreaterThan(0,null,'Price must be greater than 0'),
                    new LessThan(1000,null, 'Price too high !'),
                ]
            ])
            ->add('smokingAllowance', CheckboxType::class, [
                'required' => false,
            ])
            ->add('animalAllowance', CheckboxType::class, [
                'required' => false,
            ])
            ->add('car', EntityType::class, [
                'class' => Car::class,
                'label' => 'Vehicle',
                'choice_label' => function(Car $car){
                    return sprintf('%s %s', $car->getModel(), $car->getRegistration());
                },
                // HERE BUILD the QUERY for restricted access to car Repository, otherwise Driver is able to pick others cars => DANGEROUS security breach
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('c')
                        ->where('c.user = :user')
                        ->setParameter('user', $this->user);
                }
                
            ])
            ->add('status', EnumType::class, [
                'class' => CarshareStatus::class,
                'data' => CarshareStatus::WAITING,
                'empty_data' => CarshareStatus::WAITING->value,
            ])
            // custom preferences array add input field :
            ->add('pref', TextType::class, [
                'required' => false,
                'label' => 'Add your own additional preferences',
                'attr' => ['placeholder' => 'Separate your inputs by ; + space ']
            ]);

            $builder->get('pref')
                ->addModelTransformer(new CallbackTransformer(
                    // transform the data type to string for display on User end
                    function($prefsArray): string {
                        // transform the pref values array to a string split by semicolon
                        return implode('; ', $prefsArray);
                    },
                    // transform back to an array to store in db :
                    function($prefsString): array{
                        // transform the string back to an array
                        return explode('; ', $prefsString);
                    }
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carshare::class,
            'csrf_protection' => false,
            'error_bubbling' => true,
        ]);
    }
}
