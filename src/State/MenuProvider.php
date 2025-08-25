<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuProvider implements ProviderInterface
{
    public function __construct
    (
        private CategoryRepository $categoryRepository,
        private RequestStack $requestStack
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $request = $this->requestStack->getCurrentRequest();
        $stopList = $request->query->get('products_stopList');

        if ($stopList !== null) {
            $stopList = filter_var($stopList, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        }
        return $this->categoryRepository->findCategoriesWithTodayProducts($stopList);
    }
}
