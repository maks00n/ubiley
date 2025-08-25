<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Enum\EnumWeekday;
use App\Repository\ProductRepository;
use App\State\TodayProductsProvider;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[Vich\Uploadable]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/products',
            normalizationContext: ['groups' => ['product:read']],
            provider: TodayProductsProvider::class
        )
    ]
)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['product:read', 'category:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['product:read', 'category:read'])]
    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    private ?string $name = null;

    #[Vich\UploadableField(mapping: 'products', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    #[Groups(['product:read', 'category:read'])]
    #[ApiFilter(BooleanFilter::class)]
    private ?bool $stopList = null;

    #[ORM\Column(enumType: EnumWeekday::class)]
    #[Groups(['product:read', 'category:read'])]
    #[ApiFilter(SearchFilter::class, strategy: 'exact')]
    private ?EnumWeekday $weekday = null;

    #[ORM\Column]
    #[Groups(['product:read', 'category:read'])]
    #[ApiFilter(NumericFilter::class)]
    private ?float $price = null;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    #[Groups(['product:read', 'category:read'])]
    #[ApiProperty(readableLink: false)]
    #[ApiFilter(SearchFilter::class, strategy: 'exact')]
    private ?Category $category = null;

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

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

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function isStopList(): ?bool
    {
        return $this->stopList;
    }

    public function setStopList(bool $stopList): static
    {
        $this->stopList = $stopList;

        return $this;
    }

    public function getWeekday(): mixed
    {
        return $this->weekday;
    }

    public function setWeekday(mixed $weekday): static
    {
        $this->weekday = $weekday;

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

    #[Groups(['product:read', 'category:read'])]
    public function getImageUrl(): ?string
    {
        if (!$this->imageName) {
            return null;
        }

        return '/images/products/' . $this->imageName;
    }

    public function isAvailableToday(): bool
    {
        if (!$this->weekday) {
            return true;
        }

        return $this->weekday === EnumWeekday::getToday();
    }
}
