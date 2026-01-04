<?php

namespace App\Entity;

use App\Repository\DatoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DatoRepository::class)]
class Dato
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $tipo = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $data = null;

    #[ORM\Column]
    private ?int $quantita = null;

    #[ORM\ManyToOne(inversedBy: 'dati')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getData(): ?\DateTime
    {
        return $this->data;
    }

    public function setData(\DateTime $data = new \DateTime()): static
    {
        $this->data = $data;

        return $this;
    }

    public function getQuantità(): ?int
    {
        return $this->quantita;
    }

    public function setQuantità(int $quantita): static
    {
        $this->quantita = $quantita;

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
