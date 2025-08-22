<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\TablesStats;
use App\Repository\TablesRepository;

class TablesStatsProvider implements ProviderInterface
{
    public function __construct(
        private TablesRepository $tablesRepository
    )
    {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $data = [];
        $tables = $this->tablesRepository->findAll();
        foreach ($tables as $table)
        {
            $data[] = new TablesStats(
                $table->getId(),
                $table->getNum(),
                $table->getMaxGuests(),
                $table->getGuestsDef(),
                $table->getGuestsNow()
            );
        }
        return $data;
    }
}
