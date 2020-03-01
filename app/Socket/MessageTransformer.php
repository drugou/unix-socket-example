<?php


namespace App\Socket;


use App\Contract\MessageTransformerInterface;

/**
 * Class MessageTransformer
 *
 * Класс для преобразования сообщений в формат передачи
 *
 * @package App\Socket
 */
class MessageTransformer implements MessageTransformerInterface
{
    /**
     * @inheritDoc
     */
    public function toFormat(string $message): string
    {
        return $message . PHP_EOL;
    }

    /**
     * @inheritDoc
     */
    public function toMessage(string $formattedMessage): string
    {
        $length = strlen($formattedMessage) - strlen(PHP_EOL);
        $start = 0;
        return substr($formattedMessage, $start, $length);
    }
}