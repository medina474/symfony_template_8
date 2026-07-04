<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: JobRepository::class)]
class Job
{
    const STATUS_PENDING    = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_DONE       = 'done';
    const STATUS_FAILED     = 'failed';

    #[ORM\Id]
    #[ORM\Column(length: 36, unique: true)]
    private string $id;

    #[ORM\Column(length: 20)]
    private string $status = self::STATUS_PENDING;

    #[ORM\Column(type: 'json')]
    private array $payload = [];

    #[ORM\Column(type: 'json')]
    private array $result = [];

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $errorMessage = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE,
        insertable: false,
        updatable: false,
        options: ['default' => 'current_timestamp'])]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE,
        insertable: false,
        updatable: false,
        nullable: true)]
    private ?\DateTimeImmutable $completedAt = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    // Getters
    public function getId(): string { return $this->id; }
    public function getStatus(): string { return $this->status; }
    public function getPayload(): ?array  { return $this->payload; }
    public function getResult(): ?array  { return $this->result; }
    public function getErrorMessage(): ?string { return $this->errorMessage; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getCompletedAt(): ?\DateTimeImmutable { return $this->completedAt; }

    // Setters
    public function setPayload(array $payload): static  { $this->payload = $payload; return $this; }

    public function markProcessing(): static
    {
        $this->status = self::STATUS_PROCESSING;
        return $this;
    }

    public function markDone(?array $result): static
    {
        $this->status      = self::STATUS_DONE;
        $this->result      = $result;
        $this->completedAt = new \DateTimeImmutable();
        return $this;
    }

    public function markFailed(string $error): static
    {
        $this->status       = self::STATUS_FAILED;
        $this->errorMessage = $error;
        $this->completedAt  = new \DateTimeImmutable();
        return $this;
    }
}
