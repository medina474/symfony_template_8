<?php

namespace App\Entity;

use App\Repository\FirstNameStatRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: FirstNameStatRepository::class)]
final readonly class FirstNameStat
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    public string $id;

    public function __construct(
        #[ORM\Column()]
        public int $gender,
        #[ORM\Column()]
        public string $firstName,
        #[ORM\Column(nullable: true)]
        public ?string $yearOfBirth,
        #[ORM\Column()]
        public int $count,
    ) {
        $this->id = (new UuidV4())->toString();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
