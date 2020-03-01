<?php


namespace App\Contract;

/**
 * Interface ServerCommunicationInterface
 *
 * Интерфейс для коммуникации сервера с клиентом
 *
 * @package App\Contract
 */
interface ServerCommunicationInterface
{
    /**
     * Инициализирует сокет
     */
    public function init(): void;

    /**
     * Отправляет сообщение
     * @param string $message сообщение
     */
    public function send(string $message): void;

    /**
     * Получает сообщение
     * @return string сообщение
     */
    public function receive(): string;
}