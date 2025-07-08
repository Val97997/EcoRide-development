<?php

namespace App\Entity;

use App\Enum\CarshareStatus;
use App\Repository\CarshareRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarshareRepository::class)]
class Carshare
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $departure_date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $departure_hour = null;

    #[ORM\Column(length: 45)]
    private ?string $departure_location = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $arrival_date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $arrival_hour = null;

    #[ORM\Column(length: 45)]
    private ?string $arrival_location = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $available_seats = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'carshare')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'carshares')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Car $car = null;

    #[ORM\Column(enumType: CarshareStatus::class)]
    private ?CarshareStatus $status = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'bookHistory')]
    private Collection $passengers;

    #[ORM\Column]
    private ?bool $smokingAllowance = null;

    #[ORM\Column]
    private ?bool $animalAllowance = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $pref = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepartureDate(): ?\DateTimeInterface
    {
        return $this->departure_date;
    }

    public function setDepartureDate(\DateTimeInterface $departure_date): static
    {
        $this->departure_date = $departure_date;

        return $this;
    }

    public function getdepartureHour(): ?\DateTimeInterface
    {
        return $this->departure_hour;
    }

    public function setdepartureHour(\DateTimeInterface $departure_hour): static
    {
        $this->departure_hour = $departure_hour;

        return $this;
    }

    public function getDepartureLocation(): ?string
    {
        return $this->departure_location;
    }

    public function setDepartureLocation(string $departure_location): static
    {
        $this->departure_location = $departure_location;

        return $this;
    }

    public function getArrivalDate(): ?\DateTimeInterface
    {
        return $this->arrival_date;
    }

    public function setArrivalDate(\DateTimeInterface $arrival_date): static
    {
        $this->arrival_date = $arrival_date;

        return $this;
    }

    public function getArrivalHour(): ?\DateTimeInterface
    {
        return $this->arrival_hour;
    }

    public function setArrivalHour(\DateTimeInterface $arrival_hour): static
    {
        $this->arrival_hour = $arrival_hour;

        return $this;
    }

    public function getArrivalLocation(): ?string
    {
        return $this->arrival_location;
    }

    public function setArrivalLocation(string $arrival_location): static
    {
        $this->arrival_location = $arrival_location;

        return $this;
    }

    public function getAvailableSeats(): ?int
    {
        return $this->available_seats;
    }

    public function setAvailableSeats(int $available_seats): static
    {
        $this->available_seats = $available_seats;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

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

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): static
    {
        $this->car = $car;

        return $this;
    }

    public function getStatus(): ?CarshareStatus
    {
        return $this->status;
    }

    public function setStatus(CarshareStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getPassengers(): Collection
    {
        return $this->passengers;
    }

    public function addPassenger(User $passengers): static
    {
        if (!$this->passengers->contains($passengers)) {
            $this->passengers->add($passengers);
            $passengers->addBookHistory($this);
        }

        return $this;
    }

    public function removePassenger(User $passengers): static
    {
        if ($this->passengers->removeElement($passengers)) {
            $passengers->removeBookHistory($this);
        }

        return $this;
    }

    public function isSmokingAllowance(): ?bool
    {
        return $this->smokingAllowance;
    }

    public function setSmokingAllowance(bool $smokingAllowance): static
    {
        $this->smokingAllowance = $smokingAllowance;

        return $this;
    }

    public function isAnimalAllowance(): ?bool
    {
        return $this->animalAllowance;
    }

    public function setAnimalAllowance(bool $animalAllowance): static
    {
        $this->animalAllowance = $animalAllowance;

        return $this;
    }

    public function getPref(): ?array
    {
        return $this->pref;
    }

    public function setPref(?array $pref): static
    {
        $this->pref = $pref;

        return $this;
    }
}
