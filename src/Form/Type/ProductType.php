<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Instantiator\Instantiator;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
            ->add('name', TextType::class, [
                'get_value' => fn(Product $product) => $product->getName(),
                'update_value' => fn(string $name, Product $product) => $product->setName($name),
            ])
            ->add('sku', TextType::class, [
                'get_value' => fn(Product $product) => $product->getSku(),
                'update_value' => fn(string $sku, Product $product) => $product->setSku($sku),
            ])
            ->add('quantity', IntegerType::class, [
                'get_value' => fn(Product $product) => $product->getQuantity(),
                'update_value' => fn(int $quantity, Product $product) => $product->setQuantity($quantity),
            ])
            ->add('price', NumberType::class, [
                'get_value' => fn(Product $product) => $product->getPrice(),
                'update_value' => fn(float $price, Product $product) => $product->setPrice($price),
            ])
            ->add('category', EntityType::class, [
                'get_value' => fn(Product $product) => $product->getCategory(),
                'update_value' => fn(Category $category, Product $product) => $product->setCategory($category),
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
            'allow_extra_fields' => true,
            'factory' => fn(string $name, string $sku, int $quantity, float $price, Category $category) => new Product($name, $sku, $price, $category, $quantity),
        ]);
    }
}
