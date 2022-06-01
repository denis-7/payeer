<?php

namespace Payeer;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Payeer\Exception\PayeerHttpException;

class Payeer implements PayeerInterface {

    /**
     * @var string[]
     */
    private $_params;

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $_client;

    /**
     * @var string
     */
    private array $_arError;

    /**
     * @var string
     */
    private $_sign;

    /**
     * Конструктор
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->_params = $params;
        $this->_client = HttpClient::create([
            'base_uri' => PayeerInterface::BASEURL
        ]);
    }

    /**
     * Request to an API server
     *
     * @param PayeerRequestOptions $req
     * @return string[]
     */
    private function _request(PayeerRequestOptions $req)
    {
        $post = $req->post;
        $post['ts'] = round(microtime(true) * PayeerInterface::MSEC);
        $post_json = json_encode($post);
        $this->_sign = hash_hmac(PayeerInterface::HASHALG, $req->action . $post_json, $this->_params['key']);
        $headers = [
            'Content-Type' => 'application/json',
            'API-SIGN' => $this->_sign,
            'API-ID' => $this->_params['id']
        ];
        $response = $this->_client->request($req->method, PayeerInterface::APIURL . $req->action, [
            'headers' => $headers,
            'body' => $post_json
        ]);
        $arResponse = $response->toArray();
        if ($arResponse['success'] !== true)
        {
            $this->_arError = $arResponse['error'];
            throw new PayeerHttpException($arResponse['error']['code']);
        }
        return $arResponse;
    }

    /**
     * {@inheritdoc}
     */
    public function orders(string $pair = PayeerInterface::DEFPAIR): array
    {
        $options = PayeerRequestOptions::create()
            ->method('POST')
            ->action('orders')
            ->post(['pair' => $pair]);
        return $this->_request($options)['pairs'];
    }

    /**
     * {@inheritdoc}
     */
    public function orderCreate(array $req): array
    {
        $options = PayeerRequestOptions::create()
            ->method('POST')
            ->action('order_create')
            ->post($req);
        return $this->_request($options);
    }

    /**
     * {@inheritdoc}
     */
    public function getError(): array
    {
        return $this->_arError;
    }

    /**
     * {@inheritdoc}
     */
    public function info(): array
    {
        $options = PayeerRequestOptions::create()
            ->method('POST')
            ->action('info');
        return $this->_request($options);
    }

    /**
     * {@inheritdoc}
     */
    public function account(): array
    {
        $options = PayeerRequestOptions::create()
            ->method('POST')
            ->action('account');
        return $this->_request($options)['balances'];
    }

    /**
     * {@inheritdoc}
     */
    public function orderStatus(array $req): array
    {
        $options = PayeerRequestOptions::create()
            ->method('POST')
            ->action('order_status')
            ->post($req);
        return $this->_request($options)['order'];
    }

    /**
     * {@inheritdoc}
     */
    public function myOrders(array $req): array
    {
        $options = PayeerRequestOptions::create()
            ->method('POST')
            ->action('my_orders')
            ->post($req);
        return $this->_request($options)['items'];
    }

    /**
     * {@inheritdoc}
     */
    public function time(): int
    {
        $options = PayeerRequestOptions::create()
            ->method('POST')
            ->action('time');
        return $this->_request($options)['time'];
    }

    /**
     * {@inheritdoc}
     */
    public function ticker(string $pair = PayeerInterface::DEFPAIR): array
    {
        $options = PayeerRequestOptions::create()
            ->method('POST')
            ->action('ticker')
            ->post(['pair' => $pair]);
        return $this->_request($options)['pairs'];
    }

    /**
     * {@inheritdoc}
     */
    public function trades(string $pair = PayeerInterface::DEFPAIR): array
    {
        $options = PayeerRequestOptions::create()
            ->method('POST')
            ->action('trades')
            ->post(['pair' => $pair]);
        return $this->_request($options)['pairs'];
    }

    /**
     * {@inheritdoc}
     */
    public function orderCancel(array $req): array
    {
        $options = PayeerRequestOptions::create()
            ->method('POST')
            ->action('order_cancel')
            ->post($req);
        return $this->_request($options);
    }

    /**
     * {@inheritdoc}
     */
    public function ordersCancel(array $req): array
    {
        $options = PayeerRequestOptions::create()
            ->method('POST')
            ->action('orders_cancel')
            ->post($req);
        return $this->_request($options);
    }

    /**
     * {@inheritdoc}
     */
    public function myHistory(array $req): array
    {
        $options = PayeerRequestOptions::create()
            ->method('POST')
            ->action('my_history')
            ->post($req);
        return $this->_request($options);
    }

    /**
     * {@inheritdoc}
     */
    public function myTrades(array $req): array
    {
        $options = PayeerRequestOptions::create()
            ->method('POST')
            ->action('my_trades')
            ->post($req);
        return $this->_request($options);
    }
}

?>