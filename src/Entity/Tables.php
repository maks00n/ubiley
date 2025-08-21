<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use App\Repository\TablesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: TablesRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Patch(denormalizationContext: ['groups'=>['tables:write']])
    ]
)]
class Tables
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['tables:write'])]
    private ?int $num = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['tables:write'])]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['tables:write'])]
    private ?int $maxGuests = null;

    #[ORM\OneToMany(targetEntity: GuestList::class, mappedBy: 'tables')]
    #[SerializedName('guests')]
    private Collection $guestLists;

    public function __construct()
    {
        $this->guestLists = new ArrayCollection();
    }

    public function getGuestLists(): Collection
    {
        return $this->guestLists;
    }

    public function setGuestLists(Collection $guestLists): static
    {
        $this->guestLists = $guestLists;

        return $this;
    }

    public function getGuestsDef(): ?int
    {
        return $this->guestLists->count();
    }

    public function getGuestsNow(): ?int
    {
        return $this->guestLists->matching(
            Criteria::create()->where(Criteria::expr()->eq('isPresent', true))
        )->count();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNum(): ?int
    {
        return $this->num;
    }

    public function setNum(int $num): static
    {
        $this->num = $num;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getMaxGuests(): ?int
    {
        return $this->maxGuests;
    }

    public function setMaxGuests(?int $maxGuests): static
    {
        $this->maxGuests = $maxGuests;

        return $this;
    }

    public function __toString(): string
    {
        return "Стол $this->num";
    }
}
