<?php declare(strict_types=1);


namespace App\Server;

use App\Contract\RegulatorInterface;
use App\Contract\ServerCommunicationInterface;
use App\Contract\ServerServiceInterface;
use Throwable;

/**
 * Class Server
 *
 * Сервер
 *
 * @package App
 */
class Server
{
    /** @var ServerServiceInterface Сервис сервера */
    private ServerServiceInterface $serverService;

    /** @var ServerCommunicationInterface Сервис для связи */
    private ServerCommunicationInterface $communication;

    /** @var RegulatorInterface Регулятор зедержек */
    private RegulatorInterface $regulator;

    /**
     * Server constructor.
     * @param ServerServiceInterface $serverService Сервис сервера
     * @param ServerCommunicationInterface $communication Сервис для связи
     */
    public function __construct(
        ServerServiceInterface $serverService,
        ServerCommunicationInterface $communication,
        RegulatorInterface $regulator
    )
    {
        $this->serverService = $serverService;
        $this->communication = $communication;
        $this->regulator = $regulator;
    }

    /**
     * Запускает сервер
     * @throws Throwable
     */
    public function run(): void
    {
        $this->communication->init();
        while (true) {
            if ($this->regulator->check()) {
                $this->regulator->updateState();

                $message = $this->serverService->getMessage();

                $this->communication->send($message);
                $answer = $this->communication->receive();
                if ($this->serverService->checkAnswer($answer)) {
                    $this->serverService->showSuccessMessage($message);
                } else {
                    $this->serverService->showFailMessage($message);
                }
            }
            sleep(1);
        }
    }
}