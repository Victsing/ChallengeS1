<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['appointment:read']],
)]
#[GetCollection(
    normalizationContext: ['groups' => ['appointment:read']],
)]
#[Get(
    normalizationContext: ['groups' => ['appointment:read']],
)]
#[Post(
    normalizationContext: ['groups' => ['appointment:read']],
)]
#[Patch(
    normalizationContext: ['groups' => ['appointment:read']],
)]
#[Delete(
    normalizationContext: ['groups' => ['appointment:read']],
)]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(['appointment:read', 'user:read', 'job_ads:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['appointment:read', 'user:read', 'job_ads:read'])]
    private ?User $candidate = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['appointment:read', 'user:read', 'job_ads:read'])]
    private ?JobAds $job = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['appointment:read', 'user:read', 'job_ads:read'])]
    private ?\DateTimeInterface $time = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['appointment:read', 'user:read', 'job_ads:read'])]
    private ?bool $accepted = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidate(): ?User
    {
        return $this->candidate;
    }

    public function setCandidate(?User $candidate): self
    {
        $this->candidate = $candidate;

        return $this;
    }

    public function getJob(): ?JobAds
    {
        return $this->job;
    }

    public function setJob(?JobAds $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function isAccepted(): ?bool
    {
        return $this->accepted;
    }

    public function setAccepted(?bool $accepted): self
    {
        $this->accepted = $accepted;

        return $this;
    }
}
