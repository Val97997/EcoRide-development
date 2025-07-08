<?php

namespace App\Entity;

use App\Enum\FuelTypes;
use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    private ?string $model = null;

    #[ORM\Column(length: 255)]
    private ?string $registration = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $color = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $registration_date = null;

    /**
     * @var Collection<int, Carshare>
     */
    #[ORM\OneToMany(targetEntity: Carshare::class, mappedBy: 'car')]
    private Collection $carshares;

    #[ORM\ManyToOne(inversedBy: 'cars', cascade:['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(enumType: FuelTypes::class)]
    private ?FuelTypes $fuel = null;

    public function __construct()
    {
        $this->carshares = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getRegistration(): ?string
    {
        return $this->registration;
    }

    public function setRegistration(string $registration): static
    {
        $this->registration = $registration;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registration_date;
    }

    public function setRegistrationDate(\DateTimeInterface $registration_date): static
    {
        $this->registration_date = $registration_date;

        return $this;
    }

    /**
     * @return Collection<int, Carshare>
     */
    public function getCarshares(): Collection
    {
        return $this->carshares;
    }

    public function addCarshare(Carshare $carshare): static
    {
        if (!$this->carshares->contains($carshare)) {
            $this->carshares->add($carshare);
            $carshare->setCar($this);
        }

        return $this;
    }

    public function removeCarshare(Carshare $carshare): static
    {
        if ($this->carshares->removeElement($carshare)) {
            // set the owning side to null (unless already changed)
            if ($carshare->getCar() === $this) {
                $carshare->setCar(null);
            }
        }

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

    public function getFuel(): ?FuelTypes
    {
        return $this->fuel;
    }

    public function setFuel(FuelTypes $fuel): static
    {
        $this->fuel = $fuel;

        return $this;
    }
}
