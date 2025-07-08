<?php
// src/Document/Review.php
declare(strict_types=1);

namespace App\Document;

use App\Enum\ReviewState;
use App\Repository\ReviewRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use MongoDB\BSON\ObjectId;


 #[MongoDB\Document(repositoryClass: ReviewRepository::class)]
class Review
{
    #[MongoDB\Id]
    private string $id;

    #[MongoDB\Field(type: 'string')]
    private ?string $content;

    #[MongoDB\Field(type: 'int')]
    private int $rating;

    #[MongoDB\Field(type: 'int')]
    private int $userId;

    #[MongoDB\Field(type: 'int')]
    private int $carshareId;

    #[MongoDB\Field(type: 'date')]
    private DateTimeInterface $createdAt;

    #[MongoDB\Field(type: 'string')]
    private string $status;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getRating(): int
    {
        return $this->rating;
    }
    public function setRating(int $rating): self
    {
        if ($rating < 1 || $rating > 5) {
            throw new \InvalidArgumentException('Rating must be between 1 and 5.');
        }
        $this->rating = $rating;
        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getCarshareId(): int
    {
        return $this->carshareId;
    }

    public function setCarshareId(int $carshareId): self
    {
        $this->carshareId = $carshareId;
        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }
}