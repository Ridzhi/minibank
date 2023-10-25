<?php

namespace App\Http;

use App\UseCase\MakeTopUpBalance;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/transaction/topup', name: 'make_topup', methods: ['POST'])]
#[OA\RequestBody(
    content: new OA\JsonContent(
        properties: [
            new OA\Property(
                property: 'balance_id',
                ref: '#/components/schemas/Balance/properties/id',
            ),
            new OA\Property(
                property: 'amount',
                description: 'desired amount',
                type: 'int',
            ),
        ],
    )
)]
#[OA\Response(
    ref: '#/components/responses/balance',
    response: 200,
    description: 'return affected balance',
)]
class MakeTopUpEndpoint
{
    public function __construct(
        private MakeTopUpBalance $makeTopUpBalance,
    )
    {
    }

    public function __invoke(
        #[MapRequestPayload] MakeTopUpRequest $req,
    ): JsonResponse
    {
        return new JsonResponse(
            $this->makeTopUpBalance->do(
                $req->balance_id,
                $req->amount,
            )
        );
    }
}
