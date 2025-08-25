<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\ExistsFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\OpenApi\Factory\OpenApiFactory;
use ApiPlatform\OpenApi\Model\Operation;
use App\Dto\TablesStats;
use App\Repository\TablesRepository;
use App\State\TablesStatsProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ArrayFilter;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Unique;

#[ORM\Entity(repositoryClass: TablesRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Patch(),
        new GetCollection(uriTemplate: 'tables_stats', output: TablesStats::class, provider: TablesStatsProvider::class)
    ],
    normalizationContext: ['groups' => ['tables:read', 'guestList:read']],
    denormalizationContext: ['groups' => ['tables:write']]
)]
#[UniqueEntity(fields: ['num'], message: 'Это название уже занято')]
class Tables
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tables:read', 'guestList:read'])]
    private ?int $id = null;

    #[ORM\Column(unique: true)]
    #[Groups(['tables:read', 'guestList:read', 'tables:write'])]
    #[ApiFilter(SearchFilter::class, strategy: 'exact')]
    private ?int $num = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['tables:read', 'guestList:read', 'tables:write'])]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['tables:read', 'guestList:read', 'tables:write'])]
    private ?int $maxGuests = null;

    #[ORM\OneToMany(targetEntity: GuestList::class, mappedBy: 'tables')]
    #[SerializedName('guests')]
    #[ApiProperty(readableLink: false)]
    #[Groups(['tables:read', 'guestList:read'])]
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

    #[Groups(['tables:read', 'guestList:read'])]
    public function getGuestsDef(): ?int
    {
        return $this->guestLists->count();
    }

    #[Groups(['tables:read', 'guestList:read'])]
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
