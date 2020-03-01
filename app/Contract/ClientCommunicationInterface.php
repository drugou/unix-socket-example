<?php


namespace App\Contract;

/**
 * Interface ClientCommunication
 *
 * Интерфейс для коммуникации клиета с сервером
 *
 * @package App\Contract
 */
interface ClientCommunicationInterface
{
    /**
     * Получает сообщение от сервера
     */
    public function receive(): string;

    /**
     * Отправляет сообщение
     * @param string $message сообщение
     */
    public function send(string $message): void;
}