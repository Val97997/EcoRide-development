<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
// #[MapEntity(class: User::class, options: ['id' => 'id'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_PSEUDO', fields: ['pseudo'])]
#[UniqueEntity(fields: ['pseudo'], message: 'There is already an account with this pseudo')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, nullable:true)]
    private ?string $pseudo = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(name: 'password', nullable: true)]
    private ?string $password = '';

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = '';

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $first_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $last_name = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $phone_nb = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    /**
     * @var Collection<int, Carshare>
     */
    #[ORM\OneToMany(targetEntity: Carshare::class, mappedBy: 'user')]
    private Collection $carshare;

    #[ORM\Column]
    private ?int $credit_balance = 20;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\Column(type: Types::BLOB, length: 4294967295, nullable: true)]
    private $picture = null;

    #[ORM\Column(nullable: true)]
    private ?bool $is_verified = false;

    /**
     * @var Collection<int, Car>
     */
    #[ORM\OneToMany(targetEntity: Car::class, mappedBy: 'user')]
    private Collection $cars;
    // Here we are creating the booked carshares history, so that when User Passenger books one route, it gets saved and added to it
    // => modif needed in the BookController for saving booked carshare to history .
    /**
     * @var Collection<int, Carshare>
     */
    #[ORM\ManyToMany(targetEntity: Carshare::class, inversedBy: 'passengers')]
    private Collection $bookHistory;

    public function __construct()
    {
        $this->carshare = new ArrayCollection();
        $this->cars = new ArrayCollection();
        $this->bookHistory = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->pseudo;
    }

    /**
     * @see UserInterface
     * @return list<string>
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
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getPhoneNb(): ?string
    {
        return $this->phone_nb;
    }

    public function setPhoneNb(?string $phone_nb): static
    {
        $this->phone_nb = $phone_nb;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, Carshare>
     */
    public function getCarshares(): Collection
    {
        return $this->carshare;
    }

    public function addCarshare(Carshare $carshare): static
    {
        if (!$this->carshare->contains($carshare)) {
            $this->carshare->add($carshare);
            $carshare->setUser($this);
        }

        return $this;
    }

    public function removeCarshare(Carshare $carshare): static
    {
        if ($this->carshare->removeElement($carshare)) {
            // set the owning side to null (unless already changed)
            if ($carshare->getUser() === $this) {
                $carshare->setUser(null);
            }
        }

        return $this;
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
  
    
    public function getCreditBalance(): ?int
    {
        return $this->credit_balance;
    }
    
    public function setCreditBalance(int $credit_balance): static
    {
        $this->credit_balance = $credit_balance;
        
        return $this;
    }
    
    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }
    
    public function setBirthDate(?\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;
        
        return $this;
    }
    
    public function getPicture()
    {
        return $this->picture;
    }
    
    public function setPicture($picture): static
    {
        $this->picture = $picture;
        
        return $this;
    }
    
    public function isVerified(): ?bool
    {
        return $this->is_verified;
    }
    
    public function setIsVerified(?bool $is_verified): static
    {
        $this->is_verified = $is_verified;
        
        return $this;
    }
    public function displayImg(User $user): Response
    {
        $img = $user->getPicture();
    
        $response = new Response(stream_get_contents($img));
        $response->headers->set('Content-Type', ['image/jpeg', 'image/png', 'image/jpg']);
        return $response;
    }

    /**
     * @return Collection<int, Car>
     */
    public function getCars(): Collection
    {
        return $this->cars;
    }

    public function addCar(Car $car): static
    {
        if (!$this->cars->contains($car)) {
            $this->cars->add($car);
            $car->setUser($this);
        }

        return $this;
    }

    public function removeCar(Car $car): static
    {
        if ($this->cars->removeElement($car)) {
            // set the owning side to null (unless already changed)
            if ($car->getUser() === $this) {
                $car->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Carshare>
     */
    public function getBookHistory(): Collection
    {
        return $this->bookHistory;
    }

    public function addBookHistory(Carshare $bookHistory): static
    {
        if (!$this->bookHistory->contains($bookHistory)) {
            $this->bookHistory->add($bookHistory);
        }

        return $this;
    }

    public function removeBookHistory(Carshare $bookHistory): static
    {
        $this->bookHistory->removeElement($bookHistory);

        return $this;
    }
    public function addRole(string $role): static{

        array_push($this->roles, $role);

        return $this;
    }
}
