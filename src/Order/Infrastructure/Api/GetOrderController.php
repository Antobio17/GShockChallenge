<?php

namespace App\Order\Infrastructure\Api;

use App\Order\Application\Query\GetOrderQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * GetOrderController.
 */
class GetOrderController extends AbstractController
{

    /************************************************* PROPERTIES *************************************************/

    use HandleTrait;

    /************************************************* CONSTRUCT **************************************************/

    /**
     * CreateOrderController construct.
     *
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /************************************************ INVOKE METHOD ***********************************************/

    /**
     * CreateOrderController invoke.
     *
     * @param Request $request The request of the invoke method.
     *
     * @return JsonResponse JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $response = $this->handle(new GetOrderQuery());

        return $this->json($response, $response->getCode());
    }

}
