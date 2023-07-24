<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 2000)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\ManyToMany(targetEntity: Size::class, inversedBy: 'products')]
    private Collection $size_id;

    #[ORM\ManyToMany(targetEntity: Basket::class, mappedBy: 'product_id')]
    private Collection $baskets;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $urlimg = null;

    #[ORM\ManyToOne]
    private ?Category $category = null;

    public function __construct()
    {
        $this->size_id = new ArrayCollection();
        $this->baskets = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, Size>
     */
    public function getSizeId(): Collection
    {
        return $this->size_id;
    }

    public function addSizeId(Size $sizeId): self
    {
        if (!$this->size_id->contains($sizeId)) {
            $this->size_id->add($sizeId);
        }

        return $this;
    }

    public function removeSizeId(Size $sizeId): self
    {
        $this->size_id->removeElement($sizeId);

        return $this;
    }

    /**
     * @return Collection<int, Basket>
     */
    public function getBaskets(): Collection
    {
        return $this->baskets;
    }

    public function addBasket(Basket $basket): self
    {
        if (!$this->baskets->contains($basket)) {
            $this->baskets->add($basket);
            $basket->addProductId($this);
        }

        return $this;
    }

    public function removeBasket(Basket $basket): self
    {
        if ($this->baskets->removeElement($basket)) {
            $basket->removeProductId($this);
        }

        return $this;
    }

    public function getUrlimg(): ?string
    {
        return $this->urlimg;
    }

    public function setUrlimg(?string $urlimg): self
    {
        $this->urlimg = $urlimg;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }


}
