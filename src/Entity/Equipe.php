<?php

namespace App\Entity;

use App\Repository\EquipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipeRepository::class)]
class Equipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'equipe', targetEntity: Participant::class, orphanRemoval: true)]
    private Collection $participants;

    #[ORM\OneToMany(mappedBy: 'equipe', targetEntity: ScoreEquipe::class, orphanRemoval: true)]
    private Collection $scoreEquipes;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->scoreEquipes = new ArrayCollection();
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
     * @return Collection<int, Participant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
            $participant->setEquipe($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): static
    {
        if ($this->participants->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getEquipe() === $this) {
                $participant->setEquipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ScoreEquipe>
     */
    public function getScoreEquipes(): Collection
    {
        return $this->scoreEquipes;
    }

    public function addScoreEquipe(ScoreEquipe $scoreEquipe): static
    {
        if (!$this->scoreEquipes->contains($scoreEquipe)) {
            $this->scoreEquipes->add($scoreEquipe);
            $scoreEquipe->setEquipe($this);
        }

        return $this;
    }

    public function removeScoreEquipe(ScoreEquipe $scoreEquipe): static
    {
        if ($this->scoreEquipes->removeElement($scoreEquipe)) {
            // set the owning side to null (unless already changed)
            if ($scoreEquipe->getEquipe() === $this) {
                $scoreEquipe->setEquipe(null);
            }
        }

        return $this;
    }
}
