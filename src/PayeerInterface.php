<?php

declare(strict_types=1);

namespace Payeer;

interface PayeerInterface
{
    public const MSEC = 1000;
    public const DEFPAIR = 'BTC_USDT';
    public const BASEURL = 'https://payeer.com';
    public const APIURL = '/api/trade/';
    public const HASHALG = 'sha256';

    /**
     * Получение доступных ордеров по указанным парам.
     *
     * @param string $pair
     * @param mixed  $value
     */
    public function orders(string $pair = PayeerInterface::DEFPAIR): array;

    /**
     * Создание ордера поддерживаемых типов: лимит, маркет, стоп-лимит.
     *
     * @param array $req
     * @return array
     */
    public function orderCreate(array $req): array;

    /**
     * Получение ошибки в результате обращения к API
     *
     * @return array
     */
    public function getError(): array;

    /**
     * Получение лимитов, доступных пар и их параметров.
     *
     * @return array
     */
    public function info(): array;

    /**
     * Получение баланса пользователя.
     *
     * @return array
     */
    public function account(): array;

    /**
     * Получение подробной информации о своем ордере по его id.
     *
     * @param array $req
     * @return array
     */
    public function orderStatus(array $req): array;

    /**
     * Получение своих открытых ордеров с воможностью фильтрации.
     *
     * @param array $req
     * @return array
     */
    public function myOrders(array $req): array;

    /**
     * Проверка соединения, получение времени сервера.
     *
     * @return int timestamp сервера
     */
    public function time(): int;

    /**
     * Получение статистики цен и их колебания за последние 24 часа.
     *
     * @param string $pair
     * @return array список пар
     */
    public function ticker(string $pair = PayeerInterface::DEFPAIR): array;

    /**
     * Получение истории сделок по указанным парам.
     *
     * @param string $pair
     * @return array список пар
     */
    public function trades(string $pair = PayeerInterface::DEFPAIR): array;

    /**
     * Отмена своего ордера по его id.
     *
     * @param array $req 
     * @return boolean признак успешности запроса (true|false) 
     */
    public function orderCancel(array $req): array;

    /**
     * Отмена всех/части своих ордеров.
     *
     * @param array $req
     * @return array список отмененных ордеров
     */
    public function ordersCancel(array $req): array;

    /**
     * Получение истории своих ордеров с возможностью фильтрации и постраничной загрузки.
     *
     * @param array $req
     * @return array список ордеров
     */
    public function myHistory(array $req): array;

    /**
     * Получение своих сделок с возможностью фильтрации и постраничной загрузки.
     *
     * @param array $req
     * @return array список сделок
     */
    public function myTrades(array $req): array;

}
