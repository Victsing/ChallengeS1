<?php

namespace App\Entity;

use App\Repository\JobAdsRepository;
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
    #[Groups(['job_ads:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['job_ads:read', 'job_ads:write'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['job_ads:read', 'job_ads:write'])]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Groups(['job_ads:read', 'job_ads:write'])]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Groups(['job_ads:read', 'job_ads:write'])]
    private ?string $country = null;

    #[ORM\Column(length: 255)]
    #[Groups(['job_ads:read', 'job_ads:write'])]
    private ?string $contractType = null;

    #[ORM\Column]
    #[Groups(['job_ads:read', 'job_ads:write'])]
    private ?float $salary = null;

    #[ORM\Column(length: 255)]
    #[Groups(['job_ads:read', 'job_ads:write'])]
    private ?string $missionDuration = null;

    #[ORM\Column(length: 255)]
    #[Groups(['job_ads:read', 'job_ads:write'])]
    private ?string $jobAdStatus = null;

    #[ORM\Column]
    #[Groups(['job_ads:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['job_ads:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'jobAds')]
    #[Groups(['job_ads:read'])]
    private ?Company $company = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
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

    public function getSalary(): ?float
    {
        return $this->salary;
    }

    public function setSalary(float $salary): self
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

    public function getJobAdStatus(): ?string
    {
        return $this->jobAdStatus;
    }

    public function setJobAdStatus(string $jobAdStatus): self
    {
        $this->jobAdStatus = $jobAdStatus;

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
}
