<?php

namespace App\Tests\Functional\Controller;

use DateInterval;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Functional test for the order retrieval.
 */
class GetOrderTest extends WebTestCase
{

    /************************************************* CONSTANTS **************************************************/

    private const ENDPOINT = '/api/order/get';

    /************************************************* PROPERTIES *************************************************/

    /**
     * @var KernelBrowser|null
     */
    private static ?KernelBrowser $baseClient = NULL;

    use RecreateDatabaseTrait;

    /************************************************ TEST METHODS ************************************************/

    /**
     * Tests the order retrieval.
     */
    public function testOrderCreation(): void
    {
        self::$baseClient->request(Request::METHOD_GET, self::ENDPOINT);
        $response = self::$baseClient->getResponse();

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
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