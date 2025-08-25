<?php

namespace App\Dto;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model\Operation;
use App\State\TablesStatsProvider;
use Symfony\Component\Serializer\Attribute\Groups;

class TablesStats{
    #[Groups(['tables:read'])]
    public int $id;

    #[Groups(['tables:read'])]
    public int $num;

    #[Groups(['tables:read'])]
    public ?int $maxGuests;

    #[Groups(['tables:read'])]
    public int $booking;

    #[Groups(['tables:read'])]
    public int $guestIsPresent;

    public function __construct(
        int $id,
        int $num,
        ?int $maxGuests,
        int $booking,
        int $guestIsPresent
    ) {
        $this->id = $id;
        $this->num = $num;
        $this->maxGuests = $maxGuests;
        $this->booking = $booking;
        $this->guestIsPresent = $guestIsPresent;
    }
}
