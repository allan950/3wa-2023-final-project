<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;
    /* #[ORM\OneToMany(mappedBy: 'category_id', targetEntity: Product::class)]
    private Collection $label; */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    /* public function getLabel(): Collection
    {
        return $this->label;
    }

    public function addLabel(Product $label): self
    {
        if (!$this->label->contains($label)) {
            $this->label->add($label);
            $label->setCategory($this);
        }

        return $this;
    }

    public function removeLabel(Product $label): self
    {
        if ($this->label->removeElement($label)) {
            // set the owning side to null (unless already changed)
            if ($label->getCategory() === $this) {
                $label->setCategory(null);
            }
        }

        return $this;
    } */
}
