<?php

namespace App\Entity;

use App\Repository\JeuxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JeuxRepository::class)]
class Jeux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'jeu', targetEntity: Question::class)]
    private Collection $questions;

    #[ORM\OneToMany(mappedBy: 'jeu', targetEntity: ScoreEquipe::class, orphanRemoval: true)]
    private Collection $scoreEquipes;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
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
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setJeu($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getJeu() === $this) {
                $question->setJeu(null);
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
            $scoreEquipe->setJeu($this);
        }

        return $this;
    }

    public function removeScoreEquipe(ScoreEquipe $scoreEquipe): static
    {
        if ($this->scoreEquipes->removeElement($scoreEquipe)) {
            // set the owning side to null (unless already changed)
            if ($scoreEquipe->getJeu() === $this) {
                $scoreEquipe->setJeu(null);
            }
        }

        return $this;
    }
}
