<?php


namespace App\Contract;

/**
 * Interface ServerServiceInterface
 *
 * Интерфейс сервиса сервера
 *
 * @package App\Contract
 */
interface ServerServiceInterface
{
    /** @var string Текст сообщения от клиента для подтверждения удачной передачи сообщения */
    public const SUCCESS_MESSAGE = 'Принято';

    /**
     * Возвращает сообщение для отправки
     * @return string сообщение
     */
    public function getMessage(): string;

    /**
     * Возвращает задержку с которой нужно отправлять сообщения
     * @return int задержка в секундах
     */
    public function getDelay(): int;

    /**
     * Проверяет корректность ответа
     * @param string $answer ответ
     * @return bool true - если корректный ответ
     */
    public function checkAnswer(string $answer): bool;

    /**
     * Вывод успешного получения сообщения
     * @param string $message сообщение
     */
    public function showSuccessMessage(string $message): void;
    /**
     * Вывод не успешного получения сообщения
     * @param string $message сообщение
     */
    public function showFailMessage(string $message): void;
}