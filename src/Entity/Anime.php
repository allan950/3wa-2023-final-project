<?php

namespace App\Entity;

use App\Repository\AnimeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimeRepository::class)]
class Anime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    /* public function __construct()
    {
        $this->label = new ArrayCollection();
    } */

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
            $label->setAnime($this);
        }

        return $this;
    }

    public function removeLabel(Product $label): self
    {
        if ($this->label->removeElement($label)) {
            // set the owning side to null (unless already changed)
            if ($label->getAnime() === $this) {
                $label->setAnime(null);
            }
        }

        return $this;
    } */
}
