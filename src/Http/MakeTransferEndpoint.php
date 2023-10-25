<?php

namespace App\Http;

use App\Exception\BalanceNotFound;
use App\Exception\NotEnoughFundsOnBalance as NotEnoughFundsOnBalanceAlias;
use App\Exception\TransferLimitExceeded as TransferLimitExceededAlias;
use App\UseCase\MakeTransfer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/transaction/transfer', name: 'make_transfer', methods: ['POST'])]
#[OA\RequestBody(
    content: new OA\JsonContent(
        properties: [
            new OA\Property(
                property: 'from_balance_id',
                ref: '#/components/schemas/Balance/properties/id',
            ),
            new OA\Property(
                property: 'to_balance_id',
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
class MakeTransferEndpoint
{
    /**
     * @param MakeTransfer $makeTransfer
     */
    public function __construct(
        private MakeTransfer $makeTransfer,
    )
    {
    }

    /**
     * @param MakeTransferRequest $req
     * @return JsonResponse
     * @throws BalanceNotFound
     * @throws NotEnoughFundsOnBalanceAlias
     * @throws TransferLimitExceededAlias
     */
    public function __invoke(
        #[MapRequestPayload] MakeTransferRequest $req,
    ): JsonResponse
    {
        return new JsonResponse(
            $this->makeTransfer->do(
                $req->from_balance_id,
                $req->to_balance_id,
                $req->amount,
            )
        );
    }
}
