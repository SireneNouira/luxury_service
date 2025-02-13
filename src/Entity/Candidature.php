<?php

namespace App\Entity;

use App\Repository\CandidatureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidatureRepository::class)]
class Candidature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'candidature', cascade: ['persist', 'remove'])]
    private ?Candidate $candidat = null;

    #[ORM\ManyToOne(inversedBy: 'candidatures')]
    private ?JobOfferType $job = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidat(): ?Candidate
    {
        return $this->candidat;
    }

    public function setCandidat(?Candidate $candidat): static
    {
        $this->candidat = $candidat;

        return $this;
    }

    public function getJob(): ?JobOfferType
    {
        return $this->job;
    }

    public function setJob(?JobOfferType $job): static
    {
        $this->job = $job;

        return $this;
    }
}
