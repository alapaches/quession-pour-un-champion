<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("jeu")]
    private ?int $id = null;

    #[Groups("jeu")]
    #[ORM\Column(length: 255)]
    private ?string $intitule = null;

    #[Groups("jeu")]
    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Proposition::class, fetch: "EAGER")]
    private Collection $propositions;

    #[Groups("jeu")]
    #[ORM\ManyToOne(inversedBy: 'questions')]
    private ?Jeux $jeu = null;

    #[Groups("jeu")]
    #[ORM\ManyToOne(inversedBy: 'questions')]
    private ?Theme $theme = null;

    #[Groups("jeu")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $difficulte = null;

    #[ORM\Column(nullable: true)]
    private ?bool $completion = null;

    public function __construct()
    {
        $this->propositions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): static
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * @return Collection<int, Proposition>
     */
    public function getPropositions(): Collection
    {
        return $this->propositions;
    }

    public function addProposition(Proposition $proposition): static
    {
        if (!$this->propositions->contains($proposition)) {
            $this->propositions->add($proposition);
            $proposition->setQuestion($this);
        }

        return $this;
    }

    public function removeProposition(Proposition $proposition): static
    {
        if ($this->propositions->removeElement($proposition)) {
            // set the owning side to null (unless already changed)
            if ($proposition->getQuestion() === $this) {
                $proposition->setQuestion(null);
            }
        }

        return $this;
    }

    public function getJeu(): ?Jeux
    {
        return $this->jeu;
    }

    public function setJeu(?Jeux $jeu): static
    {
        $this->jeu = $jeu;

        return $this;
    }

    public function getTheme(): ?Theme
    {
        return $this->theme;
    }

    public function setTheme(?Theme $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function getDifficulte(): ?string
    {
        return $this->difficulte;
    }

    public function setDifficulte(string $difficulte): static
    {
        $this->difficulte = $difficulte;

        return $this;
    }

    public function isCompletion(): ?bool
    {
        return $this->completion;
    }

    public function setCompletion(?bool $completion): static
    {
        $this->completion = $completion;

        return $this;
    }
}
