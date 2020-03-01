<?php declare(strict_types=1);


namespace App\Socket;

use App\Contract\MessageTransformerInterface;
use App\Contract\ServerCommunicationInterface;
use LogicException;

/**
 * Class ServerSocket
 *
 * Серверный сокет
 *
 * @package App
 */
class ServerSocket implements ServerCommunicationInterface
{
    /** @var resource сокет */
    private $socket;

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
    public function init(): void
    {
        $this->dropPreviousSocketAddress();
        $this->initSocket();
    }

    /**
     * @inheritDoc
     */
    public function send(string $message): void
    {
        $message = $this->messageTransformer->toFormat($message);

        $connection = socket_accept($this->socket);

        $result = socket_write($connection, $message, $this->messageSize);
        if ($result === false) {
            throw new LogicException('Не получилось отправить сообщение сервером');
        }
    }

    /**
     * @inheritDoc
     */
    public function receive(): string
    {
        $connection = socket_accept($this->socket);

        if ($connection === false) {
            throw new LogicException('Не получилось выполнить ожидание сокета');
        }

        $input = socket_read($connection, $this->messageSize);
        if ($input === false) {
            throw new LogicException('Не получилось прочитать сообщение сервером');
        }

        return $this->messageTransformer->toMessage($input);
    }

    /**
     * Очищает предыдущий сокет
     */
    private function dropPreviousSocketAddress(): void
    {
        if (!file_exists($this->socketPath)) {
            return;
        }

        unlink($this->socketPath);
    }

    /**
     * Инициализирует сокет
     */
    private function initSocket(): void
    {
        $socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
        if ($socket === false) {
            throw new LogicException('Не получилось создать сокет');
        }

        $successBind = socket_bind($socket, $this->socketPath);
        if (!$successBind) {
            throw new LogicException('Не получилось задать адресс сокету');
        }

        $successListen = socket_listen($socket);
        if (!$successListen) {
            throw new LogicException('Не получилось подключиться к сокету');
        }

        $this->socket = $socket;
    }
}