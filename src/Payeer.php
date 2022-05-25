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
    private $_client;

    /**
     * @var string
     */
    private string $_arError;

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
            'base_uri' => PayeerInterface::BASEURL,
            'headers' => [
                'Content-Type' => 'application/json'
            ]
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
        $this->_sign = hash_hmac(PayeerInterface::HASHALG, $req->method . $post_json, $this->_params['key']);
        $this->_client->withOptions([
            'headers' => [
                'API-ID' => $this->_params['id'],
                'API-SIGN' => $this->_sign
            ]
        ]);
        $response = $this->_client->request($req->method, PayeerInterface::APIURL . $req->url);
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
            ->url('orders')
            ->post(['pair' => $pair]);
        $response = $this->_request($options);
        return $response['pairs'];
    }

    /**
     * {@inheritdoc}
     */
    public function orderCreate(array $req): array
    {
        $options = PayeerRequestOptions::create()
            ->method('POST')
            ->url('order_create')
            ->post($req);
        return $this->_request($options);
    }

    /**
     * {@inheritdoc}
     */
    public function getError(): string
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
            ->url('info');
        return $this->_request($options);
    }

    /**
     * {@inheritdoc}
     */
    public function account(): array
    {
        $options = PayeerRequestOptions::create()
            ->method('POST')
            ->url('account');
        $response = $this->_request($options);
        return $response['balances'];
    }

    /**
     * {@inheritdoc}
     */
    public function orderStatus(array $req): array
    {
        $options = PayeerRequestOptions::create()
            ->method('POST')
            ->url('order_status')
            ->post($req);
        $response = $this->_request($options);
        return $response['order'];
    }

    /**
     * {@inheritdoc}
     */
    public function myOrders(array $req): array
    {
        $options = PayeerRequestOptions::create()
            ->method('POST')
            ->url('my_orders')
            ->post($req);
        $response = $this->_request($options);
        return $response['items'];
    }
}

?>