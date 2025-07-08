<?php

namespace App\Form;

use App\Data\FindUserData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FindUserType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options): void{
        $builder
            ->add('pseudo', null, options: [
                'required' => true,
                'label' => 'Pseudo *',
            ])
            ->add('id', null, [
                'required' => false,
                
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary fs-3',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void{
        $resolver->setDefaults([
            'data_class' => FindUserData::class,
            'method' => 'GET',
            'crsf_protection' => false,
        ]);
    }
}