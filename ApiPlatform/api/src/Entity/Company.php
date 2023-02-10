<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['company:read']],
)]
#[Patch(
    security: 'is_granted("ROLE_ADMIN") or object.getFounder() == user',
)]
#[Post(
    denormalizationContext: ['groups' => ['company:write']],
)]
#[Get()]
#[GetCollection(
    security: 'is_granted("ROLE_ADMIN") or object.getFounder() == user',
    normalizationContext: ['groups' => ['company:read']],
)]
#[Delete(
    security: 'is_granted("ROLE_ADMIN") or object == user'
)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(['company:read', 'user:read', 'job_ads:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'company:read', 'user:write', 'company:write', 'job_ads:read'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['company:read', 'company:write'])]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(length: 255)]
    #[Groups(['company:read', 'company:write', 'user:read', 'job_ads:read'])]
    private ?string $address = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['company:read', 'company:write', 'user:read', 'job_ads:read'])]
    private ?string $website = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['company:read', 'company:write', 'user:read'])]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'companies')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['company:read', 'company:write'])]
    private ?User $founder = null;

    #[ORM\Column]
    #[Groups(['company:read', 'company:write'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::BIGINT)]
    #[Groups(['company:read', 'company:write'])]
    private ?string $siret = null;

    #[ORM\ManyToOne(inversedBy: 'companies')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['company:read', 'company:write', 'user:read'])]
    private ?CompanySizeOptions $size = null;

    #[ORM\ManyToOne(inversedBy: 'companies')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['company:read', 'company:write', 'user:read'])]
    private ?CompanyRevenueOptions $revenue = null;

    #[ORM\ManyToOne(inversedBy: 'companies')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['company:read', 'company:write', 'user:read', 'job_ads:read'])]
    private ?CompanySectorOptions $sector = null;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: JobAds::class)]
    #[Groups(['company:read', 'job_ads:read'])]
    private Collection $jobAds;

    public function __construct()
    {
        $this->jobAds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

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

    public function getFounder(): ?User
    {
        return $this->founder;
    }

    public function setFounder(?User $founder): self
    {
        $this->founder = $founder;

        return $this;
    }

    /**
     * @return Collection<int, JobAds>
     */
    public function getJobAds(): Collection
    {
        return $this->jobAds;
    }

    public function addJobAd(JobAds $jobAd): self
    {
        if (!$this->jobAds->contains($jobAd)) {
            $this->jobAds[] = $jobAd;
            $jobAd->setCompany($this);
        }

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


    public function removeJobAd(JobAds $jobAd): self
    {
        if ($this->jobAds->removeElement($jobAd)) {
            // set the owning side to null (unless already changed)
            if ($jobAd->getCompany() === $this) {
                $jobAd->setCompany(null);
            }
        }
        return $this;
    }
    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getSize(): ?CompanySizeOptions
    {
        return $this->size;
    }

    public function setSize(?CompanySizeOptions $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getRevenue(): ?CompanyRevenueOptions
    {
        return $this->revenue;
    }

    public function setRevenue(?CompanyRevenueOptions $revenue): self
    {
        $this->revenue = $revenue;

        return $this;
    }

    public function getSector(): ?CompanySectorOptions
    {
        return $this->sector;
    }

    public function setSector(?CompanySectorOptions $sector): self
    {
        $this->sector = $sector;

        return $this;
    }
}
