<?php

namespace App\Form;

use App\Entity\Actor;
use App\Entity\Movie;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('year')
            ->add('synopsis')
            ->add('poster')
            ->add('trailerLink')
            ->add('myRate')
            ->add('popularity')
            ->add('myReview')
            ->add('category', null, ['choice_label' => 'name'])
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
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
