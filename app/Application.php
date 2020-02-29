<?php declare(strict_types=1);


namespace App;

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
            $server = new Server();
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
            $client = new Client();
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
        $dotenv->load();
    }
}