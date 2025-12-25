<?php

namespace App\Entity;

use App\Repository\GoalRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GoalRepository::class)]
class Goal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $quantity = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $expiration = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $goalQuantity = null;

    #[ORM\ManyToOne(inversedBy: 'goals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getExpiration(): ?\DateTime
    {
        return $this->expiration;
    }

    public function setExpiration(?\DateTime $expiration): static
    {
        $this->expiration = $expiration;

        return $this;
    }

    public function getGoalQuantity(): ?string
    {
        return $this->goalQuantity;
    }

    public function setGoalQuantity(string $goalQuantity): static
    {
        $this->goalQuantity = $goalQuantity;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
