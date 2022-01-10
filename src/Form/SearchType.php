<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Category;
use Doctrine\DBAL\Types\DateIntervalType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateIntervalToStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType as TypeDateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher un titre',
                ]
            ])
            ->add('category', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Tous les genres',

            ])
               
            ;
                
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}