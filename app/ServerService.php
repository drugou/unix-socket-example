<?php declare(strict_types=1);


namespace App;

use Faker\Factory;
use Faker\Generator;

/**
 * Class ServerService
 *
 * Сервис сервера
 *
 * @package App
 */
class ServerService
{
    public const SUCCESS_MESSAGE = 'Принято';

    /** @var Generator фейкер */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * Возвращает сообщение для отправки
     * @return string сообщение
     */
    public function getMessage(): string
    {
        return $this->faker->name;
    }

    /**
     * Возвращает задержку с которой нужно отправлять сообщения
     * @return int задержка в секундах
     */
    public function getDelay(): int
    {
        $delay = (int)getenv('SERVER_MESSAGE_DELAY');

        if ($delay < 0) {
            $delay = 0;
        }

        return $delay;
    }

    /**
     * Проверяет корректность ответа
     * @param string $answer ответ
     * @return bool true - если корректный ответ
     */
    public function checkAnswer(string $answer): bool
    {
        return $answer === self::SUCCESS_MESSAGE;
    }

    /**
     * Вывод успешного получения сообщения
     * @param string $message сообщение
     */
    public function showSuccessClientMessage(string $message): void
    {
        echo "Сообщение {$message} принято клиентом" . PHP_EOL;
    }
}