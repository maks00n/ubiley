<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class TodayProductsProvider implements ProviderInterface
{
    public function __construct(
        private ProductRepository $productRepository,
        private RequestStack $requestStack
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $request = $this->requestStack->getCurrentRequest();

        // Получаем все параметры фильтрации
        $filters = $request->query->all();

        $filters = array_filter($filters, fn($value) => $value !== null && $value !== '');

        return $this->productRepository->findTodayProducts($filters);
    }
}
