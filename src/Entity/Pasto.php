<?php

namespace App\Entity;

use App\Repository\PastoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PastoRepository::class)]
class Pasto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $pasto = null;

    #[ORM\Column(length: 255)]
    private ?string $tipo = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $giorno = null;

    #[ORM\ManyToOne(inversedBy: 'pasto')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPasto(): ?string
    {
        return $this->pasto;
    }

    public function setPasto(string $pasto): static
    {
        $this->pasto = $pasto;

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): static
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getGiorno(): ?\DateTime
    {
        return $this->giorno;
    }

    public function setGiorno(\DateTime $data = new \DateTime()): static
    {
        $this->giorno = $data;

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
