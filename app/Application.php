<?php declare(strict_types=1);


namespace App;

use App\Client\Client;
use App\Server\Regulator;
use App\Server\Server;
use App\Client\ClientService;
use App\Server\ServerService;
use App\Socket\ClientSocket;
use App\Socket\MessageTransformer;
use App\Socket\ServerSocket;
use Dotenv;

/**
 * Class Application
 *
 * Класс приложения.
 *
 * @package App
 */
class Application
{
    /**
     * Возвращает корневую директорию приложения
     * @return string корневая директория приложения
     */
    public function getRootDir(): string
    {
        return dirname(__DIR__);
    }

    /**
     * Загружает приложение
     */
    public function boot(): void
    {
        $this->loadEnv();
    }

    /**
     * Запускает на выполнение
     */
    public function execServer(): void
    {
        try {
            $messageTransformer = new MessageTransformer();
            $communication = new ServerSocket($messageTransformer);
            $service = new ServerService();

            $regulator = new Regulator();
            $regulator->setDelay($service->getDelay());

            $server = new Server($service, $communication, $regulator);
            $server->run();
        } catch (\Throwable $exception) {
            echo $exception->getMessage() . PHP_EOL;
        }
    }

    /**
     * Запускает на выполнение
     */
    public function execClient(): void
    {
        try {
            $messageTransformer = new MessageTransformer();
            $communication = new ClientSocket($messageTransformer);
            $service = new ClientService();

            $client = new Client($service, $communication);
            $client->run();
        } catch (\Throwable $exception) {
            echo $exception->getMessage() . PHP_EOL;
        }
    }

    /**
     * Загружает ENV файл
     */
    private function loadEnv(): void
    {
        $directory = $this->getRootDir();
        $dotenv = Dotenv\Dotenv::create($directory);
        $dotenv->load();
        $dotenv->required('SOCKET_PATH')->notEmpty();
        $dotenv->required('SERVER_MESSAGE_DELAY')->isInteger();
        $dotenv->required('SOCKET_MESSAGE_SIZE')->isInteger();
        $dotenv->load();
    }
}