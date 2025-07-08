<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Car;
use App\Entity\Carshare;
use App\Entity\User;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\DateType as TypesDateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\GreaterThan;

use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Type;
use function Symfony\Component\Clock\now;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('departure_date', DateType::class, [
                'required' => true,
                'label' => 'Departure date',
                'attr' => ['placeholder' => 'Date', 'min' => date('Y-m-d')],
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => now(),
                        'message' => 'Please choose a latter date',
                    ]),
                ],
            ])
            ->add('departure_location', null,[
                'required' => true,
                'label' => false,
                'attr' => ['placeholder' => 'From...', 'maxlength' => 25],
                'constraints' => [
                    new Type([
                        'type' => 'alpha',
                        'message' => 'The value {{ value }} is not of valid {{ type }} type',
                    ]),
                ]
            ])
            ->add('arrival_location', null, [
                'required' => true,
                'label' => false,
                'attr' => ['placeholder' => 'To...', 'maxlength' => 25],
                'constraints' => [
                    new Type([
                        'type' => 'alpha',
                        'message' => 'The value {{ value }} is not of valid {{ type }} type',
                    ]),
                ]
            ])
            ->add('max', RangeType::class, [
                'required' => false,
                'label' => 'Max price',
                'attr' => ['min' => '0', 'max' => '1000', 'class'=>'price-slider-custom'],
                'constraints' => [
                    new GreaterThan([
                        'value' => 0,
                        'message' => 'The price must be greater than 0',
                    ]),
                ]
            ])
            ->add('eco', CheckboxType::class, [
                'required' => false,
                'label' => 'Eco-friendly trip',
            ])
            ->add('duration', DateIntervalType::class, [
                'with_years' => false,
                'with_months' => false,
                'with_hours' => true,
                'required' => false,
                'label' => 'Duration',
                'days' => range(0,4),
                'hours' => array_combine(range(1, 23), range(1, 23)),
                'attr' => ['placeholder' => 'HH', 'min' => '0', 'max' => '1000'],
            ])
            ->add('rating', ChoiceType::class, [
                'required' => false,
                'label' => 'Rating',
                'choices' => [
                    '3 stars' => 3,
                    '4 stars' => 4,
                    '5 stars' => 5,
                ],
                'placeholder' => 'Select a rating',
                'attr' => ['class' => 'rating-select'],
            ])
            ;
    }

    // utilisation d'une méthode GET qui permettra de passer les paramètres dans l'url
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'crsf_protection' => false,
        ]);
    }

    // la méthode getBlockPrefix() qui permet de retirer le préfixe afin d'avoir des paramètres les plus simple possible
    public function getBlockPrefix()
    {
        return '';
    }
}
