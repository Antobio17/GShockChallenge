<?php

namespace App\Tests\Functional\Controller;

use DateInterval;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Functional test for an order creation.
 */
class CreateOrderTest extends WebTestCase
{

    /************************************************* CONSTANTS **************************************************/

    private const ENDPOINT = '/api/order/create';

    /************************************************* PROPERTIES *************************************************/

    /**
     * @var KernelBrowser|null
     */
    private static ?KernelBrowser $baseClient = NULL;

    use RecreateDatabaseTrait;

    /************************************************ TEST METHODS ************************************************/

    /**
     * Tests the order creation with no error.
     */
    public function testOrderCreation(): void
    {
        $payload = array(
            'orderAtTimestamp' => date_create()->getTimestamp(),
            'reference' => 'UIYGEW23LY242',
            'remarks' => 'Test observations for the new order to be created.',
            'orderLines' => array(
                array('product' => 'Product Name 1', 'quantity' => 10, 'amount' => 28.4, 'iva' => 21)
            ),
        );

        self::$baseClient->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], json_encode($payload));
        $response = self::$baseClient->getResponse();

        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * Tests the order creation when the request has an invalid order date.
     */
    public function testOrderCreation_InvalidDate(): void
    {
        $payload = array(
            'orderAtTimestamp' => date_create()->sub(new DateInterval('P1Y'))->getTimestamp(),
            'reference' => 'UIYGEW23LY242',
            'remarks' => 'Test observations for the new order to be created.',
            'orderLines' => array(
                array('product' => 'Product Name 1', 'quantity' => 10, 'amount' => 28.4, 'iva' => 21)
            ),
        );

        self::$baseClient->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], json_encode($payload));
        $response = self::$baseClient->getResponse();

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * Tests the order creation when the request has an invalid order line quantity.
     */
    public function testOrderCreation_InvalidQuantity(): void
    {
        $payload = array(
            'orderAtTimestamp' => date_create()->getTimestamp(),
            'reference' => 'UIYGEW23LY242',
            'remarks' => 'Test observations for the new order to be created.',
            'orderLines' => array(
                array('product' => 'Product Name 1', 'quantity' => 0, 'amount' => 28.4, 'iva' => 21)
            ),
        );

        self::$baseClient->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], json_encode($payload));
        $response = self::$baseClient->getResponse();

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * Tests the order creation when the request has a reference which already exist.
     */
    public function testOrderCreation_ExistReference(): void
    {
        $payload = array(
            'orderAtTimestamp' => date_create()->getTimestamp(),
            'reference' => 'HAL96DGB6', # See in fixtures.
            'remarks' => 'Test observations for the new order to be created.',
            'orderLines' => array(
                array('product' => 'Product Name 1', 'quantity' => 2, 'amount' => 28.4, 'iva' => 21)
            ),
        );

        self::$baseClient->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], json_encode($payload));
        $response = self::$baseClient->getResponse();

        self::assertEquals(Response::HTTP_CONFLICT, $response->getStatusCode());
    }

    /*********************************************** PUBLIC METHODS ***********************************************/

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        if (self::$baseClient === NULL):
            self::$baseClient = static::createClient();
            self::$baseClient->setServerParameters(array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ));
        endif;
    }

}