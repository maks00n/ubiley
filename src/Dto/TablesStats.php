<?php

namespace App\Dto;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model\Operation;
use App\State\TablesStatsProvider;

class TablesStats{
    public int $id;

    public int $num;

    public ?int $maxGuests;

    public int $booking;

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
