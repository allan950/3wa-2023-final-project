<?php

namespace App\Form;

use App\Entity\Anime;
use App\Entity\Product;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('name')
            ->add('description', TextareaType::class, [
                'attr' => array('rows'=>'5')
            ])
            ->add('price', NumberType::class, [
                'required'      => false,
                'scale'         => 2,
                'attr'          => array(
                    '_type'         => "number",
                    'min'           => 0,
                    'step'          => 0.5,
                ),
                'rounding_mode' => 2
            ]
            )
            ->add('code')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'label'
            ])
            ->add('anime', EntityType::class, [
                'class' => Anime::class,
                'choice_label' => 'label'
            ])
            ->add('urlimg')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
