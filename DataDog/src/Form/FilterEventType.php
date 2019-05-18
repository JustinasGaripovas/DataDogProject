<?php


namespace App\Form;


use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //title. category, date, price
        $builder
            ->add('title', TextType::class, [
                'required'   => false,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'placeholder' => "",
                'required'   => false,
            ])
            ->add('earliestDate', DateType::class, [
                'widget' => 'single_text',
                'required'   => false,
            ])
            ->add('latestDate', DateType::class, [
                'widget' => 'single_text',
                'required'   => false,
            ])
            ->add('maxPrice', NumberType::class, [
                'required'   => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}