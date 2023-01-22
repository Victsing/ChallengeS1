<?php

namespace App\Entity;

use App\Repository\CompanySizeOptionsRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CompanySizeOptionsRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['company_size:read']],
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
class CompanySizeOptions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(['company_size:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Groups(['company_size:read'])]
    private ?string $size = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): self
    {
        $this->size = $size;

        return $this;
    }
}
