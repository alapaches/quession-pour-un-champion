<?php

namespace App\Entity;

use App\Repository\SonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SonRepository::class)]
class Son
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $points = null;

    #[ORM\ManyToOne(inversedBy: 'sons', fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;

    #[ORM\OneToOne(mappedBy: 'son', cascade: ['persist', 'remove'], fetch: "EAGER")]
    private ?Reponse $reponse = null;

    #[ORM\Column(nullable: true)]
    private ?bool $lock = null;

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

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): static
    {
        $this->points = $points;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getReponse(): ?Reponse
    {
        return $this->reponse;
    }

    public function setReponse(?Reponse $reponse): static
    {
        // unset the owning side of the relation if necessary
        if ($reponse === null && $this->reponse !== null) {
            $this->reponse->setSon(null);
        }

        // set the owning side of the relation if necessary
        if ($reponse !== null && $reponse->getSon() !== $this) {
            $reponse->setSon($this);
        }

        $this->reponse = $reponse;

        return $this;
    }

    public function isLock(): ?bool
    {
        return $this->lock;
    }

    public function setLock(bool $lock): static
    {
        $this->lock = $lock;

        return $this;
    }
}
