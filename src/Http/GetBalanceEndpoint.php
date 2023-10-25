<?php

namespace App\Http;

use App\Exception\BalanceNotFound;
use App\UseCase\GetBalance;
use Symfony\Component\HttpFoundation\JsonResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/balance/{id}', name: 'get_balance', methods: ['GET'])]
#[OA\Parameter(
    name: 'id',
    description: 'balance id',
    in: 'path',
)]
#[OA\Response(
    ref: '#/components/responses/balance',
    response: 200,
    description: 'return user balance by balance_id',
)]
class GetBalanceEndpoint
{
    /**
     * @param GetBalance $getBalance
     */
    public function __construct(
        private GetBalance $getBalance,
    )
    {
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws BalanceNotFound
     */
    public function __invoke(int $id): JsonResponse
    {
        return new JsonResponse($this->getBalance->do($id));
    }
}
