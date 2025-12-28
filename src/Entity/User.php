<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, Goal>
     */
    #[ORM\OneToMany(targetEntity: Goal::class, mappedBy: 'user', orphanRemoval: true, cascade: ['persist'])]
    private Collection $goals;

    #[ORM\Column(nullable: true)]
    private ?int $età = null;

    #[ORM\Column(nullable: true)]
    private ?float $peso = null;

    #[ORM\Column(nullable: true)]
    private ?float $altezza = null;

    #[ORM\Column(nullable: true)]
    private ?float $basale = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sesso = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $misuraPeso = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $misuraAltezza = null;

    public function __construct()
    {
        $this->goals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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
        return (string) $this->email;
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

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
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

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    /**
     * @return Collection<int, Goal>
     */
    public function getGoals(): Collection
    {
        return $this->goals;
    }

    public function addGoal(Goal $goal): static
    {
        if (!$this->goals->contains($goal)) {
            $this->goals->add($goal);
            $goal->setUser($this);
        }

        return $this;
    }

    public function removeGoal(Goal $goal): static
    {
        if ($this->goals->removeElement($goal)) {
            // set the owning side to null (unless already changed)
            if ($goal->getUser() === $this) {
                $goal->setUser(null);
            }
        }

        return $this;
    }

    public function getEtà(): ?int
    {
        return $this->età;
    }

    public function setEtà(?int $età): static
    {
        $this->età = $età;

        return $this;
    }

    public function getPeso(): ?float
    {
        return $this->peso;
    }

    public function setPeso(?float $peso): static
    {
        $this->peso = $peso;

        return $this;
    }

    public function getAltezza(): ?float
    {
        return $this->altezza;
    }

    public function setAltezza(?float $altezza): static
    {
        $this->altezza = $altezza;

        return $this;
    }

    public function getBasale(): ?float
    {
        return $this->basale;
    }

    public function setBasale(): static
    {
        if ($this->getSesso() == "maschio"){
            $basale = 66.5 + (13.75*$this->getPeso()) + (5.003*$this->getAltezza()) - (6.775*$this->getEtà());
        }
        elseif ($this->getSesso() == "femmina"){
            $basale = 655.1 + (9.5663*$this->getPeso()) + (1.85*$this->getAltezza()) - (4.676*$this->getEtà());
        }

        $this->basale = $basale;

        return $this;
    }

    public function getSesso(): ?string
    {
        return $this->sesso;
    }

    public function setSesso(?string $sesso): static
    {
        $this->sesso = $sesso;

        return $this;
    }

    public function getMisuraPeso(): ?string
    {
        return $this->misuraPeso;
    }

    public function setMisuraPeso(?string $misuraPeso): static
    {
        $this->misuraPeso = $misuraPeso;

        return $this;
    }

    public function getMisuraAltezza(): ?string
    {
        return $this->misuraAltezza;
    }

    public function setMisuraAltezza(?string $misuraAltezza): static
    {
        $this->misuraAltezza = $misuraAltezza;

        return $this;
    }
}
