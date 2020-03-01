<?php declare(strict_types=1);


namespace App\Client;


use App\Contract\ClientCommunicationInterface;
use App\Contract\ClientServiceInterface;
use LogicException;

/**
 * Class Client
 *
 * Клиент
 *
 * @package App
 */
class Client
{
    /** @var ClientServiceInterface Сервис клиента */
    private ClientServiceInterface $clientService;

    /** @var ClientCommunicationInterface Сервис для связи */
    private ClientCommunicationInterface $communication;

    /**
     * Client constructor.
     * @param ClientServiceInterface $clientService Сервис клиента
     * @param ClientCommunicationInterface $communication Сервис для связи
     */
    public function __construct(ClientServiceInterface $clientService, ClientCommunicationInterface $communication)
    {
        $this->clientService = $clientService;
        $this->communication = $communication;
    }

    /**
     * Запускает
     * @throws LogicException
     */
    public function run(): void
    {
        while (true) {
            $message = $this->communication->receive();
            $this->clientService->showMessage($message);
            $answer = $this->clientService->getSuccessMessage();
            $this->communication->send($answer);
        }
    }
}