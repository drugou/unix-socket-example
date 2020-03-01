<?php


namespace App\Contract;

/**
 * Interface RegulatorInterface
 *
 * Интерфейс регулятора обеспечивающего выполнение задержек
 *
 * @package App\Contract
 */
interface RegulatorInterface
{
    /**
     * Проверяет, что нужная задержка уже выдержена
     * @return bool
     */
    public function check(): bool;

    /**
     * Обновляет время относительно которого вычислять задержку
     * @param int|null $time Время, относительно которого вычислять задержку
     */
    public function updateState(int $time = null): void;
}