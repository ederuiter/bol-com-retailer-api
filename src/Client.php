<?php

namespace HarmSmits\BolComClient;

use Closure;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\Utils;
use HarmSmits\BolComClient\Exception\BearerTokenException;
use HarmSmits\BolComClient\Exception\InvalidArgumentException;
use HarmSmits\BolComClient\Exception\ProblemException;
use HarmSmits\BolComClient\Models\_Return;
use HarmSmits\BolComClient\Models\BulkCommissionRequest;
use HarmSmits\BolComClient\Models\BulkCommissionResponse;
use HarmSmits\BolComClient\Models\BulkProcessStatusRequest;
use HarmSmits\BolComClient\Models\CancelOrderItemsRequest;
use HarmSmits\BolComClient\Models\ChangeTransportRequest;
use HarmSmits\BolComClient\Models\Commission;
use HarmSmits\BolComClient\Models\CreateOfferExportRequest;
use HarmSmits\BolComClient\Models\CreateOfferRequest;
use HarmSmits\BolComClient\Models\CreateProductContentRequest;
use HarmSmits\BolComClient\Models\CreateReplenishmentRequest;
use HarmSmits\BolComClient\Models\CreateReturnRequest;
use HarmSmits\BolComClient\Models\CreateSubscriptionRequest;
use HarmSmits\BolComClient\Models\CreateUnpublishedOfferReportRequest;
use HarmSmits\BolComClient\Models\DeliveryOptionsRequest;
use HarmSmits\BolComClient\Models\DeliveryOptionsResponse;
use HarmSmits\BolComClient\Models\InventoryResponse;
use HarmSmits\BolComClient\Models\KeySetResponse;
use HarmSmits\BolComClient\Models\OfferInsights;
use HarmSmits\BolComClient\Models\Order;
use HarmSmits\BolComClient\Models\PerformanceIndicators;
use HarmSmits\BolComClient\Models\PickupTimeSlotsRequest;
use HarmSmits\BolComClient\Models\PickupTimeSlotsResponse;
use HarmSmits\BolComClient\Models\Problem;
use HarmSmits\BolComClient\Models\ProcessStatus;
use HarmSmits\BolComClient\Models\ProcessStatusResponse;
use HarmSmits\BolComClient\Models\ProductLabelsRequest;
use HarmSmits\BolComClient\Models\ReducedOrders;
use HarmSmits\BolComClient\Models\ReplenishmentResponse;
use HarmSmits\BolComClient\Models\ReplenishmentsResponse;
use HarmSmits\BolComClient\Models\RetailerOffer;
use HarmSmits\BolComClient\Models\ReturnRequest;
use HarmSmits\BolComClient\Models\ReturnsResponse;
use HarmSmits\BolComClient\Models\SalesForecastResponse;
use HarmSmits\BolComClient\Models\SearchTerms;
use HarmSmits\BolComClient\Models\Shipment;
use HarmSmits\BolComClient\Models\ShipmentRequest;
use HarmSmits\BolComClient\Models\ShipmentsResponse;
use HarmSmits\BolComClient\Models\ShippingLabelRequest;
use HarmSmits\BolComClient\Models\SubscriptionResponse;
use HarmSmits\BolComClient\Models\SubscriptionsResponse;
use HarmSmits\BolComClient\Models\UpdateOfferPriceRequest;
use HarmSmits\BolComClient\Models\UpdateOfferRequest;
use HarmSmits\BolComClient\Models\UpdateOfferStockRequest;
use HarmSmits\BolComClient\Models\UpdateReplenishmentRequest;
use HarmSmits\BolComClient\Models\UpdateSubscriptionRequest;
use HarmSmits\BolComClient\Models\ValidationReportResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Throwable;

/**
 * Class Client
 * @method BulkCommissionResponse getCommissions(BulkCommissionRequest $body)
 * @method PromiseInterface getCommissionsAsync(BulkCommissionRequest $body)
 * @method Commission getCommission(string $ean, string $condition, int $unitPrice)
 * @method PromiseInterface getCommissionAsync(string $ean, string $condition, int $unitPrice)
 * @method ProcessStatus createProductContent(CreateProductContentRequest $body = NULL)
 * @method PromiseInterface createProductContentAsync(CreateProductContentRequest $body = NULL)
 * @method ValidationReportResponse getContentValidationReport(string $uploadId)
 * @method PromiseInterface getContentValidationReportAsync(string $uploadId)
 * @method OfferInsights getOfferInsights(string $offerId, string $period, int $numberOfPeriods, array $name)
 * @method PromiseInterface getOfferInsightsAsync(string $offerId, string $period, int $numberOfPeriods, array $name)
 * @method PerformanceIndicators getPerformanceIndicatorInsights(array $name, string $year, string $week)
 * @method PromiseInterface getPerformanceIndicatorInsightsAsync(array $name, string $year, string $week)
 * @method SalesForecastResponse getSalesForecastInsights(string $offerId, int $weeksAhead)
 * @method PromiseInterface getSalesForecastInsightsAsync(string $offerId, int $weeksAhead)
 * @method SearchTerms getSearchTermsInsights(string $searchTerm, string $period, int $numberOfPeriods, bool $relatedSearchTerms = NULL)
 * @method PromiseInterface getSearchTermsInsightsAsync(string $searchTerm, string $period, int $numberOfPeriods, bool $relatedSearchTerms = NULL)
 * @method InventoryResponse getInventory(int $page = null, array $quantity = null, string $stock = null, string $state = NULL, string $query = NULL)
 * @method PromiseInterface getInventoryAsync(int $page = NULL, array $quantity = NULL, string $stock = NULL, string $state = NULL, string $query = NULL)
 * @method string getInvoices(string $periodStartDate = NULL, string $periodEndDate = NULL)
 * @method PromiseInterface getInvoicesAsync(string $periodStartDate = NULL, string $periodEndDate = NULL)
 * @method string getInvoice(string $invoiceId)
 * @method PromiseInterface getInvoiceAsync(string $invoiceId)
 * @method getInvoiceSpecifications(string $invoiceId, int $page = NULL)
 * @method PromiseInterface getInvoiceSpecificationsAsync(string $invoiceId, int $page = 1)
 * @method ProcessStatus createOffer(CreateOfferRequest $body)
 * @method PromiseInterface createOfferAsync(CreateOfferRequest $body)
 * @method ProcessStatus createOfferExport(CreateOfferExportRequest $body)
 * @method PromiseInterface createOfferExportAsync(CreateOfferExportRequest $body)
 * @method string getOfferExport(string $reportId)
 * @method PromiseInterface getOfferExportAsync(string $reportId)
 * @method ProcessStatus createUnpublishedOfferReport(CreateUnpublishedOfferReportRequest $body)
 * @method PromiseInterface createUnpublishedOfferReportAsync(CreateUnpublishedOfferReportRequest $body)
 * @method string getUnpublishedOfferReport(string $reportId)
 * @method PromiseInterface getUnpublishedOfferReportAsync(string $reportId)
 * @method RetailerOffer getOffer(string $offerId)
 * @method PromiseInterface getOfferAsync(string $offerId)
 * @method ProcessStatus updateOffer(string $offerId, UpdateOfferRequest $body)
 * @method PromiseInterface updateOfferAsync(string $offerId, UpdateOfferRequest $body)
 * @method ProcessStatus deleteOffer(string $offerId)
 * @method PromiseInterface deleteOfferAsync(string $offerId)
 * @method ProcessStatus updateOfferPrice(string $offerId, UpdateOfferPriceRequest $body)
 * @method PromiseInterface updateOfferPriceAsync(string $offerId, UpdateOfferPriceRequest $body)
 * @method ProcessStatus updateOfferStock(string $offerId, UpdateOfferStockRequest $body)
 * @method PromiseInterface updateOfferStockAsync(string $offerId, UpdateOfferStockRequest $body)
 * @method ReducedOrders getOrders(int $page = NULL, string $fulfilmentMethod = NULL, string $status = NULL)
 * @method PromiseInterface getOrdersAsync(int $page = NULL, string $fulfilmentMethod = NULL, string $status = NULL)
 * @method ProcessStatus cancelOrderItems(CancelOrderItemsRequest $body)
 * @method PromiseInterface cancelOrderItemsAsync(CancelOrderItemsRequest $body)
 * @method ProcessStatus updateShipment(ShipmentRequest $body)
 * @method PromiseInterface updateShipmentAsync(ShipmentRequest $body)
 * @method Order getOrder(string $orderId)
 * @method PromiseInterface getOrderAsync(string $orderId)
 * @method ProcessStatusResponse getProcessStatus(string $entityId, string $eventType, int $page = NULL)
 * @method PromiseInterface getProcessStatusAsync(string $entityId, string $eventType, int $page = NULL)
 * @method ProcessStatusResponse getBulkProcessStatus(BulkProcessStatusRequest $body)
 * @method PromiseInterface getBulkProcessStatusAsync(BulkProcessStatusRequest $body)
 * @method ProcessStatus getDetailedProcessStatus(string $processStatusId)
 * @method PromiseInterface getDetailedProcessStatusAsync(string $processStatusId)
 * @method ReplenishmentsResponse getReplenishments(string $reference = null, string $ean = null, string $startDate = NULL, string $endDate = NULL, array $state = NULL, int $page = NULL)
 * @method PromiseInterface getReplenishmentsAsync(string $reference = NULL, string $ean = NULL, string $startDate = NULL, string $endDate = NULL, array $state = NULL, int $page = NULL)
 * @method ProcessStatus createReplenishment(CreateReplenishmentRequest $body)
 * @method PromiseInterface createReplenishmentAsync(CreateReplenishmentRequest $body)
 * @method PickupTimeSlotsResponse createReplenishmentsPickupTimeSlots(PickupTimeSlotsRequest $body)
 * @method PromiseInterface createReplenishmentsPickupTimeSlotsAsync(PickupTimeSlotsRequest $body)
 * @method string createReplenishmentsProductLabels(ProductLabelsRequest $body)
 * @method PromiseInterface createReplenishmentsProductLabelsAsync(ProductLabelsRequest $body)
 * @method ReplenishmentResponse getReplenishment(string $replenishmentId)
 * @method PromiseInterface getReplenishmentAsync(string $replenishmentId)
 * @method ProcessStatus updateReplenishment(string $replenishmentId, UpdateReplenishmentRequest $body)
 * @method PromiseInterface updateReplenishmentAsync(string $replenishmentId, UpdateReplenishmentRequest $body)
 * @method string getReplenishmentLoadCarrierLabels(string $replenishmentId, string $labelType)
 * @method PromiseInterface getReplenishmentLoadCarrierLabelsAsync(string $replenishmentId, string $labelType)
 * @method string getReplenishmentsPickList(string $replenishmentId)
 * @method PromiseInterface getReplenishmentsPickListAsync(string $replenishmentId)
 * @method ReturnsResponse getReturns(int $page = NULL, bool $handled = NULL, string $fulfilmentMethod = NULL)
 * @method PromiseInterface getReturnsAsync(int $page = NULL, bool $handled = NULL, string $fulfilmentMethod = NULL)
 * @method ProcessStatus createReturn(CreateReturnRequest $body)
 * @method PromiseInterface createReturnAsync(CreateReturnRequest $body)
 * @method _Return getReturn(string $returnId)
 * @method PromiseInterface getReturnAsync(string $returnId)
 * @method ProcessStatus updateReturn(int $rmaId, ReturnRequest $body)
 * @method PromiseInterface updateReturnAsync(int $rmaId, ReturnRequest $body)
 * @method ShipmentsResponse getShipments(int $page = NULL, string $fulfilmentMethod = NULL, string $orderId = NULL)
 * @method PromiseInterface getShipmentsAsync(int $page = NULL, string $fulfilmentMethod = NULL, string $orderId = NULL)
 * @method Shipment getShipment(string $shipmentId)
 * @method PromiseInterface getShipmentAsync(string $shipmentId)
 * @method ProcessStatus createShippingLabel(ShippingLabelRequest $body)
 * @method PromiseInterface createShippingLabelAsync(ShippingLabelRequest $body)
 * @method DeliveryOptionsResponse getShippingLabelsDeliveryOptions(DeliveryOptionsRequest $body)
 * @method PromiseInterface getShippingLabelsDeliveryOptionsAsync(DeliveryOptionsRequest $body)
 * @method string getShippingLabel(string $shippingLabelId)
 * @method PromiseInterface getShippingLabelAsync(string $shippingLabelId)
 * @method SubscriptionsResponse getSubscriptions()
 * @method PromiseInterface getSubscriptionsAsync()
 * @method ProcessStatus createSubscription(CreateSubscriptionRequest $body)
 * @method PromiseInterface createSubscriptionAsync(CreateSubscriptionRequest $body)
 * @method KeySetResponse getSubscriptionsSignatureKeys()
 * @method PromiseInterface getSubscriptionsSignatureKeysAsync()
 * @method ProcessStatus createSubscriptionTest()
 * @method PromiseInterface createSubscriptionTestAsync()
 * @method SubscriptionResponse getSubscription(string $subscriptionId)
 * @method PromiseInterface getSubscriptionAsync(string $subscriptionId)
 * @method ProcessStatus updateSubscription(string $subscriptionId, UpdateSubscriptionRequest $body)
 * @method PromiseInterface updateSubscriptionAsync(string $subscriptionId, UpdateSubscriptionRequest $body)
 * @method ProcessStatus deleteSubscription(string $subscriptionId)
 * @method PromiseInterface deleteSubscriptionAsync(string $subscriptionId)
 * @method ProcessStatus updateTransport(string $transportId, ChangeTransportRequest $body)
 * @method PromiseInterface updateTransportAsync(string $transportId, ChangeTransportRequest $body)
 * @package HarmSmits\BolComClient
 */
class Client
{
    /** @var string domain for live API requests */
    const API_URL_LIVE = "https://api.bol.com/retailer/";

    /** @var string domain for authentications */
    const API_AUTH_LIVE = "https://login.bol.com/token";

    /** @var \GuzzleHttp\Client */
    protected \GuzzleHttp\Client $client;

    /** @var Request request dispatcher */
    protected Request $request;

    /** @var Populator */
    protected Populator $populator;

    /** @var string holds the current API client id */
    protected string $clientId;

    /** @var string holds the current API client secret */
    protected string $clientSecret;

    /** @var string holds the obtained bearer token */
    protected string $bearerToken = "";

    /** @var int holds the expiration date of the token */
    protected int $bearerTimestamp;

    /** @var int the TTL for the current bearer token */
    protected int $bearerTtl;

    /** @var int the difference which should be account for (1 second is more than enough) */
    protected int $bearerDiff;

    /** @var bool the bearer token lock so that we know when to disable it */
    protected bool $bearerTokenLock = false;

    protected int $maxRateLimitTimeout;

    /**
     * Client constructor.
     *
     * @param string $clientId
     * @param string $clientSecret
     * @param int    $maxRateLimitTimeout
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $clientId, string $clientSecret, int $maxRateLimitTimeout = 60)
    {
        if (!$clientId || !$clientSecret) {
            throw new InvalidArgumentException("Unable to obtain bearer token");
        }

        $stack = HandlerStack::create();
        $stack->push(Middleware::mapRequest($this->getRequestHandler()));
        $stack->push(Middleware::retry($this->getRetryHandler()));

        $this->client = new \GuzzleHttp\Client(["handler" => $stack]);
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->maxRateLimitTimeout = $maxRateLimitTimeout;

        $this->request = new Request();
        $this->populator = new Populator();
    }

    /**
     * Unwrap an array of async requests, very useful when stacking multiple
     *
     * @param array $promises
     *
     * @return array
     * @throws Throwable
     */
    public function unwrap(array $promises): array
    {
        return Utils::unwrap($promises);
    }

    /**
     * Get the retry handler
     *
     * @return Closure
     */
    private function getRetryHandler(): Closure
    {
        return function ($retries, ?RequestInterface $request, ?ResponseInterface $response,
            ?RequestException $exception) {
            if (!$response || $response->getStatusCode() > 500 || ($exception && $exception instanceof ConnectException))
                return false;

            if (($wait = $response->getHeaderLine("retry-after")) && intval($wait) <= $this->maxRateLimitTimeout) {
                sleep(intval($wait));
                return true;
            }

            return false;
        };
    }

    /**
     * Get the request handler
     *
     * @return Closure
     */
    private function getRequestHandler(): Closure
    {
        return function (RequestInterface $request) {
            if (!$this->bearerTokenLock)
                return $request->withHeader('Authorization', 'Bearer ' . $this->getBearerToken());
            return $request;
        };
    }

    /**
     * Dispatch the request. This is some serious sorcery not to be touched.
     *
     * @param string $method
     * @param array  $args
     *
     * @return array|string|Promise
     * @throws ProblemException|GuzzleException
     */
    public function __call(string $method, array $args)
    {
        if (($async = substr($method, -5) === 'Async')) {
            $method = substr($method, 0, -5);
        }

        [$method, $url, $data, $response] = call_user_func_array([$this->request, $method], $args);

        if ($async) {
            return $this->handleAsyncRequest($method, $url, $data, $response);
        } else {
            return $this->handleRequest($method, $url, $data, $response);
        }
    }

    /**
     * Handle a non-blocking request
     *
     * @param $method
     * @param $url
     * @param $data
     * @param $responseFormat
     *
     * @return PromiseInterface
     */
    private function handleAsyncRequest($method, $url, $data, array $responseFormat): PromiseInterface
    {
        return $this->client->requestAsync($method, $url, $data)
            ->then(function (ResponseInterface $response) use (&$responseFormat) {
                return $this->handleResponse($response, $responseFormat);
            });
    }

    /**
     * Handle a blocking request
     *
     * @param       $method
     * @param       $url
     * @param       $data
     * @param array $responseFormat
     *
     * @return array|mixed|StreamInterface
     * @throws ProblemException|GuzzleException
     */
    private function handleRequest($method, $url, $data, array $responseFormat)
    {
        try {
            $result = $this->client->request($method, $url, $data);
        } catch (RequestException $exception) {
            $response = $exception->getResponse();
            return $this->handleResponse($response, $responseFormat);
        }

        return $this->handleResponse($result, $responseFormat);
    }

    /**
     * Handle the response accordingly
     *
     * @param ResponseInterface $response
     * @param array             $responseFormat
     *
     * @return array|mixed|StreamInterface
     * @throws ProblemException
     */
    private function handleResponse(ResponseInterface $response, array $responseFormat)
    {
        if ($responseFormat && isset($responseFormat[$response->getStatusCode()])) {
            $body = json_decode($response->getBody(), true);
            $result = $this->populator->populate($responseFormat[$response->getStatusCode()], $body);

            if ($result instanceof Problem) {
                throw new ProblemException($result);
            }

            return $result;
        } else {
            return $response->getBody();
        }
    }

    /**
     * Set the current bearer token (for requests)
     *
     * @param string $bearerToken
     * @param int    $ttl
     * @param int    $diff
     *
     * @throws InvalidArgumentException
     */
    private function setBearerToken(string $bearerToken, int $ttl, int $diff = 1)
    {
        if (!$bearerToken || !$ttl)
            throw new InvalidArgumentException("Invalid arguments for token bearer");
        $this->bearerDiff = $diff;
        $this->bearerToken = $bearerToken;
        $this->bearerTimestamp = time();
        $this->bearerTtl = $ttl;
    }

    /**
     * Get a bearer token
     *
     * @throws InvalidArgumentException|GuzzleException|BearerTokenException
     */
    private function getBearerToken(): string
    {
        $this->bearerTokenLock = true;

        if (empty($this->bearerToken) || time() - $this->bearerTtl + 5 >= $this->bearerTimestamp) {
            $data = $this->obtainBearerToken();
            $this->setBearerToken($data['access_token'], (int)$data['expires_in']);
        }

        $this->bearerTokenLock = false;

        return $this->bearerToken;
    }

    /**
     * Obtain a bearer token
     *
     * @return mixed
     * @throws BearerTokenException|GuzzleException
     */
    private function obtainBearerToken()
    {
        $request = $this->client->request(
            "POST",
            self::API_AUTH_LIVE . "?grant_type=client_credentials",
            [
                'auth' => [
                    $this->clientId,
                    $this->clientSecret
                ]
            ]);

        if ($request->getStatusCode() != 200)
            throw new BearerTokenException("Unable to obtain bearer token");
        return (json_decode($request->getBody(), true));
    }
}
