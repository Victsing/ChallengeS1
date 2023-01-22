<?php

namespace App\Entity;

use App\Repository\CompanySectorOptionsRepository;
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
    #[Groups(['company_sector:read'])]
    private ?string $sector = null;

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
}
