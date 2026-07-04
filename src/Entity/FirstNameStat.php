<?php

namespace App\Entity;

use App\Repository\FirstNameStatRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: FirstNameStatRepository::class)]
final readonly class FirstNameStat
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::BIGINT)]
    public int $id;

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

    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
