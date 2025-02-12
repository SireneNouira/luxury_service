<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $companyName = null;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'clients')]
    private Collection $activityType;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $number = null;

   
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $notes = null;

    /**
     * @var Collection<int, JobOfferType>
     */
    #[ORM\OneToMany(targetEntity: JobOfferType::class, mappedBy: 'client', orphanRemoval: true)]
    private Collection $jobOfferTypes;

    #[ORM\OneToOne(inversedBy: 'client', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    public function __construct()
    {
        $this->activityType = new ArrayCollection();
        $this->jobOfferTypes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): static
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getActivityType(): Collection
    {
        return $this->activityType;
    }

    public function addActivityType(Category $activityType): static
    {
        if (!$this->activityType->contains($activityType)) {
            $this->activityType->add($activityType);
        }

        return $this;
    }

    public function removeActivityType(Category $activityType): static
    {
        $this->activityType->removeElement($activityType);

        return $this;
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

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

  

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

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
            $jobOfferType->setClient($this);
        }

        return $this;
    }

    public function removeJobOfferType(JobOfferType $jobOfferType): static
    {
        if ($this->jobOfferTypes->removeElement($jobOfferType)) {
            // set the owning side to null (unless already changed)
            if ($jobOfferType->getClient() === $this) {
                $jobOfferType->setClient(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
