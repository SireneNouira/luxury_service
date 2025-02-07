<?php

namespace App\Entity;

use App\Repository\CandidateRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidateRepository::class)]
class Candidate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

 

    #[ORM\ManyToOne(inversedBy: 'candidates')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Gender $gender = null;

    #[ORM\Column(length: 255)]
    private ?string $cv = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profil_picture = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $birthplace = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $passport_file = null;

    #[ORM\Column(length: 255)]
    private ?string $current_location = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nationality = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deleted_at = null;

    #[ORM\ManyToOne(inversedBy: 'candidates')]
    private ?PassportState $passport_state = null;

    /**
     * @var Collection<int, Availability>
     */
    #[ORM\OneToMany(targetEntity: Availability::class, mappedBy: 'state')]
    private Collection $availabilities;

    #[ORM\ManyToOne(inversedBy: 'candidates')]
    private ?Experience $experience = null;

    #[ORM\OneToOne(inversedBy: 'candidate', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    public function __construct()
    {
        $this->availabilities = new ArrayCollection();
     
            $this->created_at = new DateTimeImmutable(); 
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(string $cv): static
    {
        $this->cv = $cv;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getProfilPicture(): ?string
    {
        return $this->profil_picture;
    }

    public function setProfilPicture(?string $profil_picture): static
    {
        $this->profil_picture = $profil_picture;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBirthPlace(): ?string
    {
        return $this->birthplace;
    }

    public function setBirthPlace(?string $birthplace): static
    {
        $this->birthplace = $birthplace;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthDate(\DateTimeInterface $birthdate): static
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getPassportFile(): ?string
    {
        return $this->passport_file;
    }

    public function setPassportFile(?string $passport_file): static
    {
        $this->passport_file = $passport_file;

        return $this;
    }

    public function getCurrentLocation(): ?string
    {
        return $this->current_location;
    }

    public function setCurrentLocation(string $current_location): static
    {
        $this->current_location = $current_location;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): static
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?\DateTimeImmutable $deleted_at): static
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }

    public function getPassportState(): ?PassportState
    {
        return $this->passport_state;
    }

    public function setPassportState(?PassportState $passport_state): static
    {
        $this->passport_state = $passport_state;

        return $this;
    }

    /**
     * @return Collection<int, Availability>
     */
    public function getAvailabilities(): Collection
    {
        return $this->availabilities;
    }

    public function addAvailability(Availability $availability): static
    {
        if (!$this->availabilities->contains($availability)) {
            $this->availabilities->add($availability);
            $availability->setState($this);
        }

        return $this;
    }

    public function removeAvailability(Availability $availability): static
    {
        if ($this->availabilities->removeElement($availability)) {
            // set the owning side to null (unless already changed)
            if ($availability->getState() === $this) {
                $availability->setState(null);
            }
        }

        return $this;
    }

    
    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    public function setGender(?Gender $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getExperience(): ?Experience
    {
        return $this->experience;
    }

    public function setExperience(?Experience $experience): static
    {
        $this->experience = $experience;

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
