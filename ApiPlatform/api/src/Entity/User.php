<?php

namespace App\Entity;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\EmailVerifier;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\ResetPassword;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\ApiResource;
use App\Controller\ResetPasswordToken;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource()]
#[Patch(
    denormalizationContext: ['groups' => ['user:getToken']],
    name: 'reset-password-token',
    uriTemplate: '/users/reset/password/token',
    controller: ResetPasswordToken::class,
)]
#[Patch(
    denormalizationContext: ['groups' => ['user:setPassword']],
    name: 'reset-password',
    uriTemplate: '/users/reset/password',
    controller: ResetPassword::class
)]
#[Patch(
    name: 'email-verification',
    uriTemplate: '/users/email/verification',
    controller: EmailVerifier::class
)]
#[Patch(
    denormalizationContext: ['groups' => ['user:write']],
    security: 'is_granted("ROLE_ADMIN") or object == user'
)]
#[Post(
    denormalizationContext: ['groups' => ['user:write']]
)]
#[Get(
    security: 'is_granted("ROLE_ADMIN") or object == user',
    normalizationContext: ['groups' => ['user:read']]
)]
#[GetCollection(
    security: 'is_granted("ROLE_ADMIN")',
    normalizationContext: ['groups' => ['user:read']]
)]
#[Delete(
    security: 'is_granted("ROLE_ADMIN") or object == user'
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(['user:read', 'company:read', 'job_ads:read', 'appointment:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['user:read', 'user:write', 'user:getToken', 'company:read', 'job_ads:read', 'appointment:read'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['user:read', 'user:write'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['user:setPassword', 'user:write'])]
    private ?string $password = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:setPassword'])]
    private ?string $token = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:write', 'company:read', 'job_ads:read', 'appointment:read'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:write', 'company:read', 'job_ads:read', 'appointment:read'])]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'])]
    #[Groups(['user:read', 'user:write'])]
    private ?string $birthdate = null;

    #[ORM\Column]
    private ?bool $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'founder', targetEntity: Company::class)]
    #[Groups(['user:read', 'user:write'])]
    private Collection $companies;

    #[ORM\ManyToMany(targetEntity: JobAds::class, mappedBy: 'candidates')]
    #[Groups(['user:read', 'user:write'])]
    private Collection $jobApplications;

    #[ORM\OneToMany(mappedBy: 'candidate', targetEntity: Appointment::class)]
    #[Groups(['user:read', 'user:write', 'appointment:read'])]
    private Collection $appointments;

    public function __construct()
    {
        $this->roles = array('ROLE_USER');
        $this->companies = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->jobApplications = new ArrayCollection();
        $this->appointments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getBirthdate(): ?string
    {
        return $this->birthdate;
    }

    public function setBirthdate(string $birthdate): self
    {
        $this->birthdate = $birthdate;

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
            $company->setFounder($this);
        }

        return $this;
    }

    public function removeCompany(Company $company): self
    {
        if ($this->companies->removeElement($company)) {
            // set the owning side to null (unless already changed)
            if ($company->getFounder() === $this) {
                $company->setFounder(null);
            }
        }
        return $this;
    }
    public function isVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, JobAds>
     */
    public function getJobApplications(): Collection
    {
        return $this->jobApplications;
    }

    public function addJobApplication(JobAds $jobApplication): self
    {
        if (!$this->jobApplications->contains($jobApplication)) {
            $this->jobApplications[] = $jobApplication;
            $jobApplication->addCandidate($this);
        }

        return $this;
    }

    public function removeJobApplication(JobAds $jobApplication): self
    {
        if ($this->jobApplications->removeElement($jobApplication)) {
            $jobApplication->removeCandidate($this);
        }

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
            $appointment->setCandidate($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getCandidate() === $this) {
                $appointment->setCandidate(null);
            }
        }

        return $this;
    }
}
