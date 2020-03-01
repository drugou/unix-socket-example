<?php declare(strict_types=1);


namespace App\Client;

use App\Contract\ClientServiceInterface;
use App\Contract\ServerServiceInterface;

/**
 * Class ClientService
 *
 * Сервис клиента
 *
 * @package App
 */
class ClientService implements ClientServiceInterface
{
    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return ServerServiceInterface::SUCCESS_MESSAGE;
    }

    /**
     * @inheritDoc
     */
    public function showMessage(string $message): void
    {
        echo $message . PHP_EOL;
    }
}