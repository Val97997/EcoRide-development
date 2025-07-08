<?php

namespace App\Form;

use App\Document\Review;
use Doctrine\DBAL\Types\DateImmutableType as TypesDateImmutableType;
use Doctrine\ODM\MongoDB\Types\DateImmutableType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function Symfony\Component\Clock\now;

class ReviewType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('created_at', DateTimeType::class, [
                'label' => false,
                'data' => now(),
            ])
            ->add('content', TextareaType::class, [
                'label' => false,
                'attr' => ['rows' => '5', 'cols' => '45', 'wrap' => 'hard', 'placeholder' => 'Enter your commentary'],
            ])
            ->add('rating', HiddenType::class)
            ->add('save', SubmitType::class, [
                'label' => 'Submit review',
                'attr' => ['class' => 'btn btn-dark']
            ])
            ->add('status', HiddenType::class, [
                'data' => 'pending',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}