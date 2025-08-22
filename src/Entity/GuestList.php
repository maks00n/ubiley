<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\OpenApi\Model\Operation;
use App\Repository\GuestListRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: GuestListRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Patch(denormalizationContext: ['groups' => ['guestList:write']])],
)]
#[ApiResource(
    uriTemplate: 'tables/{id}/guests',
    operations: [new GetCollection(openapi: new Operation(tags: ['Tables']))],
    uriVariables: [
        'id' => new Link(
            fromProperty: 'guestLists',
            fromClass: Tables::class
        )
    ],
)]
class GuestList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['guestList:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['guestList:write'])]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['guestList:write'])]
    private ?bool $isPresent = null;

    #[ORM\ManyToOne(targetEntity: Tables::class, inversedBy: 'guestLists')]
    #[ORM\JoinColumn(name: 'tables_id', referencedColumnName: 'id')]
    #[ApiProperty(readableLink: true, writableLink: false)]
    #[Groups(['guestList:write'])]
    private ?Tables $tables = null;

    public function getTables(): ?Tables
    {
        return $this->tables;
    }

    public function setTables(?Tables $tables): static
    {
        $this->tables = $tables;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getIsPresent(): ?bool
    {
        return $this->isPresent;
    }

    public function setIsPresent(bool $isPresent): static
    {
        $this->isPresent = $isPresent;

        return $this;
    }
}
