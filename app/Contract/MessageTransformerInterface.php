<?php


namespace App\Contract;

/**
 * Interface MessageTransformerInterface
 *
 * Интерфейс трансформера сообщений
 *
 * @package App\Contract
 */
interface MessageTransformerInterface
{
    /**
     * Преобразует сообщение в формат для передачи
     * @param string $message сообщение
     * @return string сообщение в формате для передачи
     */
    public function toFormat(string $message): string;

    /**
     * Преобразует в исходное сообщение из форматированного
     * @param string $formattedMessage форматированное сообщение
     * @return string сообщение
     */
    public function toMessage(string $formattedMessage): string;
}