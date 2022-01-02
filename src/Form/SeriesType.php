<?php

namespace App\Form;

use App\Entity\Actor;
use App\Entity\Series;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('category', null, [
                'choice_label' => 'name',
            ])
            ->add('startYear')
            ->add('endYear')
            ->add('synopsis')
            ->add('poster')
            ->add('trailerLink')
            ->add('myRate')
            ->add('popularity')
            ->add('myReview')
            ->add('seasonsCount')
            ->add('episodesCount')
            ->add('actors', EntityType::class, [
                'class' => Actor::class,
                //function to return the actor full name (to concatenate columns)
                'choice_label' => function (Actor $actor) {
                    return $actor->getFirstName() . ' ' . $actor->getLastName();
                },
                'multiple' => true,
                'expanded' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.firstName', 'ASC');
                },
                'by_reference' => false,]
            )
            ->add('allocineLink')
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Series::class,
        ]);
    }
}
