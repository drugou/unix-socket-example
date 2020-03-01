<?php declare(strict_types=1);


namespace App\Server;

use App\Contract\ServerServiceInterface;
use Faker\Factory;
use Faker\Generator;

/**
 * Class ServerService
 *
 * Сервис сервера
 *
 * @package App
 */
class ServerService implements ServerServiceInterface
{
    /** @var Generator фейкер */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return $this->faker->name;
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function checkAnswer(string $answer): bool
    {
        return $answer === self::SUCCESS_MESSAGE;
    }

    /**
     * @inheritDoc
     */
    public function showSuccessMessage(string $message): void
    {
        echo "Сообщение {$message} принято клиентом" . PHP_EOL;
    }

    /**
     * @inheritDoc
     */
    public function showFailMessage(string $message): void
    {
        echo "Сообщение {$message} не принято клиентом" . PHP_EOL;
    }
}