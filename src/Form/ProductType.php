<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit',
                'attr' => ['placeholder' => 'Taper le nom du produit'],
            ])
            ->add('shortDescription', TextareaType::class, [
                'label' => 'Description courte',
                'attr' => ['placeholder' => 'Taper une description courte assez parlante pour le visiteur'],
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix du produit',
                'attr' => ['placeholder' => 'Taper le prix du produit en €'],
            ])
            ->add('mainPicture', UrlType::class, [
                'label' => 'Image du produit',
                'attr' => ['placeholder' => 'Taper une URL d\'image'],
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie du produit',
                'placeholder' => '-- Choisir une catégorie --',
                'class' => Category::class,
                'choice_label' => function (Category $category) {
                    return $category->getName();
                },
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();

            /** @var Product */
            $product = $event->getData();

            // if (null === $product->getId()) {
            //     $form->add('category', EntityType::class, [
            //         'label' => 'Catégorie du produit',
            //         'placeholder' => '-- Choisir une catégorie --',
            //         'class' => Category::class,
            //         'choice_label' => function (Category $category) {
            //             return $category->getName();
            //         },
            //     ]);
            // }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
