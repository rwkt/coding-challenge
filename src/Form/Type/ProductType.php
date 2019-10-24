<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Instantiator\Instantiator;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TypeError;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('sku', TextType::class)
            ->add('quantity', NumberType::class)
            ->add('price', NumberType::class)
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'invalid_message' => 'Invalid category value',
            ])
        ;
    }

    /**
     * In case user forcibly submits invalid data that PHP can't handle, create new instance without constructor.
     *
     * It allows to still have validation performed without 500 page.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'csrf_protection' => false,
            'empty_data' => function (FormInterface $form) {
                $name = $form->get('name')->getData();
                $sku = $form->get('sku')->getData();
                $quantity = $form->get('quantity')->getData();
                $category = $form->get('category')->getData();
                $price = $form->get('price')->getData();

                try {
                    return new Product($name, $sku, $price, $category, $quantity);
                } catch (TypeError $error) {
                    return (new Instantiator())->instantiate(Product::class);
                }
            },
        ]);
    }
}
