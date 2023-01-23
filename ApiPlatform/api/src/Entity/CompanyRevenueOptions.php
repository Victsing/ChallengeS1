<?php

namespace App\Entity;

use App\Repository\CompanyRevenueOptionsRepository;
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

#[ORM\Entity(repositoryClass: CompanyRevenueOptionsRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['company_revenue:read']],
    denormalizationContext: ['groups' => ['company_revenue:write']],
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
class CompanyRevenueOptions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(['company_revenue:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Groups(['company_revenue:read', 'company:read', 'company_revenue:write'])]
    private ?string $revenue = null;

    #[ORM\OneToMany(mappedBy: 'revenue', targetEntity: Company::class)]
    private Collection $companies;

    public function __construct()
    {
        $this->companies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRevenue(): ?string
    {
        return $this->revenue;
    }

    public function setRevenue(string $revenue): self
    {
        $this->revenue = $revenue;

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
            $company->setRevenue($this);
        }

        return $this;
    }

    public function removeCompany(Company $company): self
    {
        if ($this->companies->removeElement($company)) {
            // set the owning side to null (unless already changed)
            if ($company->getRevenue() === $this) {
                $company->setRevenue(null);
            }
        }

        return $this;
    }
}
