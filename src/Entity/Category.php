<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
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
    private ?string $name = null;

    // Ajoutez une relation ManyToMany dans la classe Category
    #[ORM\ManyToMany(targetEntity: Candidate::class, inversedBy: 'Category')]
    private Collection $candidates;

    /**
     * @var Collection<int, JobOfferType>
     */
    #[ORM\ManyToMany(targetEntity: JobOfferType::class, mappedBy: 'category')]
    private Collection $jobOfferTypes;

    public function __construct()
    {
        $this->candidates = new ArrayCollection();
        $this->jobOfferTypes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Candidate>
     */
    public function getCandidates(): Collection
    {
        return $this->candidates;
    }

    public function addCandidate(Candidate $candidate): static
    {
        if (!$this->candidates->contains($candidate)) {
            $this->candidates->add($candidate);
            $candidate->setCategory($this);
        }

        return $this;
    }

    public function removeCandidate(Candidate $candidate): static
    {
        if ($this->candidates->removeElement($candidate)) {
            // set the owning side to null (unless already changed)
            if ($candidate->getJobCategory() === $this) {
                $candidate->setJobCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, JobOfferType>
     */
    public function getJobOfferTypes(): Collection
    {
        return $this->jobOfferTypes;
    }

    public function addJobOfferType(JobOfferType $jobOfferType): static
    {
        if (!$this->jobOfferTypes->contains($jobOfferType)) {
            $this->jobOfferTypes->add($jobOfferType);
            $jobOfferType->addCategory($this);
        }

        return $this;
    }

    public function removeJobOfferType(JobOfferType $jobOfferType): static
    {
        if ($this->jobOfferTypes->removeElement($jobOfferType)) {
            $jobOfferType->removeCategory($this);
        }

        return $this;
    }
}
