<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Product
{
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Serializer\Groups(groups={"product"})
     *
     */
    private ?int $id = null;

    /**
     * @Assert\NotNull(message="Name cannot be empty")
     * @ORM\Column(type="string", nullable=false)
     *
     * @Serializer\Groups(groups={"product"})
     */
    private string $name;

    /**
     * @Assert\NotNull(message="SKU cannot be empty")
     * @Assert\Length(value=5, exactMessage="SKU must be have exactly {{ limit }} characters.")
     *
     * @ORM\Column(type="string", nullable=false)
     *
     * @Serializer\Groups(groups={"product"})
     */
    private string $sku;

    /**
     * @Assert\Type(type=Category::class, message="Nope")
     *
     * @ORM\ManyToOne(targetEntity=Category::class)
     * @ORM\JoinColumn(onDelete="SET NULL")
     *
     * @Serializer\Groups(groups={"product"})
     */
    private Category $category;

    /**
     * @Assert\NotNull(message="Quantity cannot be empty")
     *
     * @ORM\Column(type="integer", nullable=false)
     *
     * @Serializer\Groups(groups={"product"})
     */
    private int $quantity;

    /**
     * @Assert\NotNull(message="Quantity cannot be empty")
     *
     * @ORM\Column(type="float", nullable=false)
     *
     * @Serializer\Groups(groups={"product"})
     */
    private float $price;

    public function __construct(string $name, string $sku, float $price, Category $category, int $quantity)
    {
        $this->name = $name;
        $this->sku = $sku;
        $this->category = $category;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
}
