<?php

namespace App\Entity;

use App\Repository\CandidatureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CandidatureRepository::class)]
#[UniqueEntity(fields: ['candidat', 'job'], message: 'Un candidat ne peut postuler qu\'une seule fois pour un même job.')]
class Candidature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // #[ORM\OneToOne(inversedBy: 'candidature', cascade: ['persist', 'remove'])]
    // private ?Candidate $candidat = null;

    // #[ORM\ManyToOne(inversedBy: 'candidatures')]
    // private ?JobOfferType $job = null;
    #[ORM\ManyToOne(targetEntity: Candidate::class, inversedBy: 'candidatures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Candidate $candidat = null;

    #[ORM\ManyToOne(targetEntity: JobOfferType::class)]
    #[ORM\JoinColumn(nullable: false)]
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
