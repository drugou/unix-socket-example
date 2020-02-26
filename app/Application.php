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
     * Загружает ENV файл
     */
    protected function loadEnv(): void
    {
        $directory = $this->getRootDir();
        $dotenv = Dotenv\Dotenv::create($directory);
        $dotenv->load();
    }
}