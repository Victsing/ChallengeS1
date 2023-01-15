<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\PizzaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;

#[ApiResource(
    normalizationContext: ['groups' => ['pizza_read']],
    denormalizationContext: ['groups' => ['pizza_write']]
)]
#[Get(
    normalizationContext: ['groups' => ['pizza_get', 'pizza_read']]
)]
#[GetCollection(
    normalizationContext: ['groups' => ['pizza_cget', 'pizza_read']]
)]
#[Post(
    security: "is_granted('DIRECTOR_NEW', user)"
)]
#[Patch(
    security: "is_granted('DIRECTOR_NEW', user)"
)]
#[Delete(
    security: "is_granted('DIRECTOR_OLD', user)"
)]
#[ORM\Entity(repositoryClass: PizzaRepository::class)]
#[UniqueEntity('name', message: 'Nom déjà utilisé.')]
class Pizza
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['pizza_read', 'pizza_write'])]
    #[NotBlank]
    #[NotNull]
    #[Type('string')]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['pizza_get', 'pizza_write'])]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Ingredient::class, inversedBy: 'pizzas')]
    #[Groups(['pizza_get', 'pizza_write'])]
    private Collection $ingredients;

    #[ORM\OneToMany(mappedBy: 'pizza', targetEntity: Detail::class)]
    private Collection $details;

    #[ORM\ManyToOne]
    #[Gedmo\Blameable(on: 'create')]
    #[Groups('pizza_get')]
    private ?User $owner = null;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->details = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }

    /**
     * @return Collection<int, Detail>
     */
    public function getDetails(): Collection
    {
        return $this->details;
    }

    public function addDetail(Detail $detail): self
    {
        if (!$this->details->contains($detail)) {
            $this->details[] = $detail;
            $detail->setPizza($this);
        }

        return $this;
    }

    public function removeDetail(Detail $detail): self
    {
        if ($this->details->removeElement($detail)) {
            // set the owning side to null (unless already changed)
            if ($detail->getPizza() === $this) {
                $detail->setPizza(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
