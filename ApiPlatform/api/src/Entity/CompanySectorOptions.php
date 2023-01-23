<?php

namespace App\Entity;

use App\Repository\CompanySectorOptionsRepository;
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

#[ORM\Entity(repositoryClass: CompanySectorOptionsRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['company_sector:read']],
    denormalizationContext: ['groups' => ['company_sector:write']],
)]
#[POST(
    security: 'is_granted("ROLE_ADMIN")',
)]
#[PATCH(
    security: 'is_granted("ROLE_ADMIN")',
)]
#[DELETE(
    security: 'is_granted("ROLE_ADMIN")',
)]
#[Get()]
#[GetCollection()]
class CompanySectorOptions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(['company_sector:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Groups(['company_sector:read', 'company:read', 'company_sector:write'])]
    private ?string $sector = null;

    #[ORM\OneToMany(mappedBy: 'sector', targetEntity: Company::class)]
    private Collection $companies;

    public function __construct()
    {
        $this->companies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSector(): ?string
    {
        return $this->sector;
    }

    public function setSector(string $sector): self
    {
        $this->sector = $sector;

        return $this;
    }

    /**
     * @return Collection<int, Company>
     */
    public function getCompanies(): Collection
    {
        return $this->companies;
    }

    public function addCompany(Company $company): self
    {
        if (!$this->companies->contains($company)) {
            $this->companies[] = $company;
            $company->setSector($this);
        }

        return $this;
    }

    public function removeCompany(Company $company): self
    {
        if ($this->companies->removeElement($company)) {
            // set the owning side to null (unless already changed)
            if ($company->getSector() === $this) {
                $company->setSector(null);
            }
        }

        return $this;
    }
}
