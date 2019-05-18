<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Event;
use App\Repository\CategoryRepository;
use function Sodium\add;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array(
                'attr' => [
                    'class' => "form-control"
                ]
            ))
            ->add('excerpt')
            ->add('description')
            ->add('date', DateType::class, [
                'placeholder' => [
                    'year' => date("Y"), 'month' => date("m"), 'day' => date("d"),
                ]
            ])
            ->add('price', IntegerType::class)
            ->add('eventCategories', EntityType::class, [
                'class' => Category::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'name',
                'query_builder' => function(CategoryRepository $cr){
                    return $cr->createQueryBuilder('u')->orderBy('u.name', 'ASC');
                },
            ])
            ->add('lat',HiddenType::class)
            ->add('lng',HiddenType::class)
            ->add('image', FileType::class, ['label' => 'Image', 'required' => false,]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
