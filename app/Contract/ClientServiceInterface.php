<?php


namespace App\Contract;

/**
 * Interface ClientServiceInterface
 *
 * Интерфейс сервиса клиента
 *
 * @package App\Contract
 */
interface ClientServiceInterface
{
    /**
     * Возвращает сообщение успешного извлечения сообщения
     * @return string сообщение успешного извлечения сообщения
     */
    public function getSuccessMessage(): string;

    /**
     * Отображает полученное сообщение
     * @param string $message сообщение
     */
    public function showMessage(string $message): void;
}