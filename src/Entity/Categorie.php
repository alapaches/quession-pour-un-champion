<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Son::class, orphanRemoval: true)]
    private Collection $sons;

    public function __construct()
    {
        $this->sons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Son>
     */
    public function getSons(): Collection
    {
        return $this->sons;
    }

    public function addSon(Son $son): static
    {
        if (!$this->sons->contains($son)) {
            $this->sons->add($son);
            $son->setCategorie($this);
        }

        return $this;
    }

    public function removeSon(Son $son): static
    {
        if ($this->sons->removeElement($son)) {
            // set the owning side to null (unless already changed)
            if ($son->getCategorie() === $this) {
                $son->setCategorie(null);
            }
        }

        return $this;
    }
}
