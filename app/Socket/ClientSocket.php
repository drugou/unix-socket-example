<?php declare(strict_types=1);


namespace App\Socket;


use App\Contract\ClientCommunicationInterface;
use App\Contract\MessageTransformerInterface;
use LogicException;

/**
 * Class ClientSocket
 *
 * Клиентский сокет
 *
 * @package App
 */
class ClientSocket implements ClientCommunicationInterface
{
    /** @var string путь к доменному сокету Unix */
    private string $socketPath;

    /** @var int размер сообщений в байтах */
    private int $messageSize;

    /** @var MessageTransformerInterface трансформер сообщений */
    private MessageTransformerInterface $messageTransformer;

    /**
     * ClientSocket constructor.
     * @param MessageTransformerInterface $messageTransformer Конвертер сообщений
     */
    public function __construct(MessageTransformerInterface $messageTransformer)
    {
        $this->socketPath = (string)getenv('SOCKET_PATH');
        $this->messageSize = (int)getenv('SOCKET_MESSAGE_SIZE');
        $this->messageTransformer = $messageTransformer;
    }

    /**
     * @inheritDoc
     */
    public function receive(): string
    {
        $socket = $this->getSocket();
        $input = socket_read($socket, $this->messageSize, PHP_NORMAL_READ);
        if ($input === false) {
            throw new LogicException('Не получилось прочитать сообщение сервером');
        }

        return $this->messageTransformer->toMessage($input);
    }

    /**
     * @inheritDoc
     */
    public function send(string $message): void
    {
        $message = $this->messageTransformer->toFormat($message);

        $socket = $this->getSocket();

        $result = socket_write($socket, $message, $this->messageSize);
        if ($result === false) {
            throw new LogicException('Не получилось отправить сообщение клиентом');
        }
    }

    /**
     * @return resource
     */
    private function getSocket()
    {
        $socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
        if ($socket === false) {
            throw new LogicException('Не получилось создать сокет');
        }

        $connect = socket_connect($socket, $this->socketPath);
        if (!$connect) {
            new LogicException('Не получилось подключиться к сокету!');
        }

        return $socket;
    }
}