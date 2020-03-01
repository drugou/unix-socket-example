<?php declare(strict_types=1);


namespace App\Server;

use App\Contract\RegulatorInterface;

/**
 * Class Regulator
 *
 * Обеспечивает выполнение задержек для выполнения комманд
 *
 * @package App
 */
class Regulator implements RegulatorInterface
{
    /** @var int задержка */
    private int $delay;

    /** @var int Время относительно которого вычислять задержку */
    private int $time;

    /** @var int Время последней проверки */
    private int $newTime;

    /** @var bool Пропустить задержку */
    private bool $missOne;

    /**
     * Regulator constructor.
     * @param int|null $delay Зедержка в секундах
     * @param int|null $time Время относительно которого вычислять задержку
     * @param bool $missOne Пропустить одну задержку
     */
    public function __construct(int $delay = null, int $time = null, bool $missOne = true)
    {
        if ($time === null || $time < 0) {
            $time = time();
        }
        $this->time = $time;

        if ($delay === null || $delay < 0) {
            $delay = 0;
        }
        $this->setDelay($delay);

        $this->setMissOne($missOne);
    }

    /**
     * Задает задержку
     * @param int $delay Задержка в секундах
     */
    public function setDelay(int $delay): void
    {
        $this->delay = $delay;
    }

    /**
     * @inheritDoc
     */
    public function check(): bool
    {
        $this->newTime = time();
        $second = ($this->newTime - $this->time);
        return $this->missOne || $second > $this->delay;
    }

    /**
     * Пропускает одну задержку
     * @param bool $missOne true - пропустить
     */
    public function setMissOne(bool $missOne): void
    {
        $this->missOne = $missOne;
    }

    /**
     * @inheritDoc
     */
    public function updateState(int $time = null): void
    {
        if ($time === null) {
            $time = $this->newTime;
        }
        $this->time = $time;
        $this->missOne = false;
    }
}