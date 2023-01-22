<?php

namespace App\Entity;

use App\Repository\CompanyRevenueOptionsRepository;
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
    #[Groups(['company_revenue:read'])]
    private ?string $revenue = null;

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
}
