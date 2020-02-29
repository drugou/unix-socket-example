<?php declare(strict_types=1);


namespace App;

/**
 * Class ClientService
 *
 * Сервис клиента
 *
 * @package App
 */
class ClientService
{
    /**
     * Возвращает сообщение успешного извлечения сообщения
     * @return string сообщение успешного извлечения сообщения
     */
    public function getSuccessMessage(): string
    {
        return ServerService::SUCCESS_MESSAGE;
    }

    /**
     * Отображает полученное сообщение
     * @param string $message сообщение
     */
    public function showMessage(string $message): void
    {
        echo $message . PHP_EOL;
    }
}