<?php

namespace App\Order\Infrastructure\Api;

use App\Order\Application\Model\CreateOrderResponse;
use App\Order\Application\Query\CreateOrderQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\ToolsHelper;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * CreateOrderController.
 */
class CreateOrderController extends AbstractController
{

    /************************************************* CONSTANTS **************************************************/

    public const REQUEST_FIELD_ORDER_AT = 'orderAtTimestamp';
    public const REQUEST_FIELD_REFERENCE = 'reference';
    public const REQUEST_FIELD_REMARKS = 'remarks';
    public const REQUEST_FIELD_ORDER_LINES = 'orderLines';
    public const REQUEST_FIELD_PRODUCT = 'product';
    public const REQUEST_FIELD_QUANTITY = 'quantity';
    public const REQUEST_FIELD_AMOUNT = 'amount';
    public const REQUEST_FIELD_IVA = 'iva';

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
        $orderAtTimestamp = ToolsHelper::getParamFromRequest($request, static::REQUEST_FIELD_ORDER_AT);
        $reference = ToolsHelper::getParamFromRequest($request, static::REQUEST_FIELD_REFERENCE);
        $remarks = ToolsHelper::getParamFromRequest($request, static::REQUEST_FIELD_REMARKS);
        $orderLines = ToolsHelper::getParamFromRequest($request, static::REQUEST_FIELD_ORDER_LINES);

        $createOrderResponse = $this->_validateRequest($orderAtTimestamp, $reference, $remarks, $orderLines);

        if ($createOrderResponse === NULL):
            $createOrderResponse = $this->handle(new CreateOrderQuery(
                $orderAtTimestamp, $reference, $remarks, $orderLines
            ));
            $status = $createOrderResponse->getOrder() !== NULL ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        else:
            $status = Response::HTTP_BAD_REQUEST;
        endif;

        return $this->json($createOrderResponse, $status);
    }

    /********************************************** PRIVATE METHODS *********************************************/

    /**
     * Validates the request of the order creation.
     *
     * @param int|null $orderAtTimestamp Timestamp of the order date.
     * @param string|null $reference Reference of the order.
     * @param string|null $remarks Remarks of the order.
     * @param array|null $orderLines Order lines of the order.
     *
     * @return CreateOrderResponse|null CreateOrderResponse|null
     */
    private function _validateRequest(?int   $orderAtTimestamp, ?string $reference, ?string $remarks,
                                      ?array $orderLines): ?CreateOrderResponse
    {
        $requiredFields = array(
            static::REQUEST_FIELD_ORDER_AT => $orderAtTimestamp,
            static::REQUEST_FIELD_REFERENCE => $reference,
            static::REQUEST_FIELD_REMARKS => $remarks,
            static::REQUEST_FIELD_ORDER_LINES => $orderLines
        );

        $messages = array_merge(
            ToolsHelper::validateRequiredRequestFields($requiredFields),
            $this->_validateOrderLines($orderLines)
        );

        return empty($messages) ? NULL : CreateOrderResponse::ofError($messages);
    }

    /**
     * Validates the order lines of the order creation.
     *
     * @param array|null $orderLines Data of the order lines.
     *
     * @return array array
     */
    public function _validateOrderLines(?array $orderLines): array
    {
        if (!empty($orderLines)):
            foreach ($orderLines as $orderLine):
                $requiredFields = array(
                    static::REQUEST_FIELD_PRODUCT => $orderLine[static::REQUEST_FIELD_PRODUCT] ?? NULL,
                    static::REQUEST_FIELD_QUANTITY => $orderLine[static::REQUEST_FIELD_QUANTITY] ?? NULL,
                    static::REQUEST_FIELD_AMOUNT => $orderLine[static::REQUEST_FIELD_AMOUNT] ?? NULL,
                    static::REQUEST_FIELD_IVA => $orderLine[static::REQUEST_FIELD_IVA] ?? NULL,
                );
                $numericFields = array(
                    static::REQUEST_FIELD_QUANTITY => $orderLine[static::REQUEST_FIELD_QUANTITY] ?? NULL,
                    static::REQUEST_FIELD_AMOUNT => $orderLine[static::REQUEST_FIELD_AMOUNT] ?? NULL,
                    static::REQUEST_FIELD_IVA => $orderLine[static::REQUEST_FIELD_IVA] ?? NULL,
                );

                if (
                    !is_numeric($orderLine[static::REQUEST_FIELD_QUANTITY]) ||
                    $orderLine[static::REQUEST_FIELD_QUANTITY] > 0
                ):
                    $quantityValidation = array('The value of the quantity must be greater than zero.');
                endif;

                $messages = array_merge(
                    ToolsHelper::validateRequiredRequestFields($requiredFields),
                    ToolsHelper::validateRequestNumericFields($numericFields),
                    $quantityValidation ?? array()
                );
            endforeach;
        endif;

        return $messages ?? array();
    }

}
