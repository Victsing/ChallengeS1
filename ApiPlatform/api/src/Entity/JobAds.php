<?php

namespace App\Entity;

use App\Repository\JobAdsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: JobAdsRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['job_ads:read']],
    denormalizationContext: ['groups' => ['job_ads:write']],
)]
#[Patch(
    security: 'is_granted("ROLE_ADMIN") or object.getFounder() == user',
)]
#[Post()]
#[Get()]
#[GetCollection()]
#[Delete()]
class JobAds
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(['job_ads:read', 'company:read', 'user:read', 'appointment:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['job_ads:read', 'job_ads:write', 'company:read', 'user:read', 'appointment:read'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['job_ads:read', 'job_ads:write', 'company:read', 'user:read', 'appointment:read'])]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Groups(['job_ads:read', 'job_ads:write', 'company:read', 'user:read', 'appointment:read'])]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Groups(['job_ads:read', 'job_ads:write', 'company:read', 'user:read', 'appointment:read'])]
    private ?string $country = null;

    #[ORM\Column(length: 255)]
    #[Groups(['job_ads:read', 'job_ads:write', 'company:read', 'user:read', 'appointment:read'])]
    private ?string $contractType = null;

    #[ORM\Column]
    #[Groups(['job_ads:read', 'job_ads:write', 'company:read', 'user:read', 'appointment:read'])]
    private ?string $salary = null;

    #[ORM\Column(length: 255)]
    #[Groups(['job_ads:read', 'job_ads:write', 'company:read', 'user:read', 'appointment:read'])]
    private ?string $missionDuration = null;

    #[ORM\Column]
    #[Groups(['job_ads:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['job_ads:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'jobAds')]
    #[Groups(['job_ads:read', 'job_ads:write', 'company:read'])]
    private ?Company $company = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'jobApplications')]
    #[Groups(['job_ads:read', 'job_ads:write'])]
    private Collection $candidates;

    #[ORM\OneToMany(mappedBy: 'job', targetEntity: Appointment::class)]
    private Collection $appointments;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->candidates = new ArrayCollection();
        $this->appointments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getContractType(): ?string
    {
        return $this->contractType;
    }

    public function setContractType(string $contractType): self
    {
        $this->contractType = $contractType;

        return $this;
    }

    public function getSalary(): ?string
    {
        return $this->salary;
    }

    public function setSalary(string $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getMissionDuration(): ?string
    {
        return $this->missionDuration;
    }

    public function setMissionDuration(string $missionDuration): self
    {
        $this->missionDuration = $missionDuration;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getCandidates(): Collection
    {
        return $this->candidates;
    }

    public function addCandidate(User $candidate): self
    {
        if (!$this->candidates->contains($candidate)) {
            $this->candidates[] = $candidate;
        }

        return $this;
    }

    public function removeCandidate(User $candidate): self
    {
        $this->candidates->removeElement($candidate);

        return $this;
    }

    /**
     * @return Collection<int, Appointment>
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): self
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments[] = $appointment;
            $appointment->setJob($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getJob() === $this) {
                $appointment->setJob(null);
            }
        }

        return $this;
    }
}
