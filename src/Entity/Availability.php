<?php

namespace App\Entity;

use App\Repository\AvailabilityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvailabilityRepository::class)]
class Availability
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'availabilities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Candidate $state = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getState(): ?Candidate
    {
        return $this->state;
    }

    public function setState(?Candidate $state): static
    {
        $this->state = $state;

        return $this;
    }
}
