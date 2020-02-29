<?php declare(strict_types=1);


namespace App;


use LogicException;

/**
 * Class Client
 *
 * Клиент
 *
 * @package App
 */
class Client
{
    /** @var string путь к доменному сокету Unix */
    private string $socketPath;

    /**
     * Server constructor.
     */
    public function __construct()
    {
        $this->socketPath = (string)getenv('SOCKET_PATH');
    }

    /**
     * Запускает
     * @throws LogicException
     */
    public function run(): void
    {
        $socket = $this->socketInit();
        $service = new ClientService();
        $successMessage = $service->getSuccessMessage() . PHP_EOL;
        while (true) {
            $successSocketAccept = socket_accept($socket);
            if ($successSocketAccept === false) {
                throw new LogicException('Не получилось выполнить ожидание сокета');
            }

            $input = socket_read($socket, 1024, PHP_NORMAL_READ);
            if ($input === false) {
                throw new LogicException('Не получилось прочитать сообщение клиентом');
            }
            $service->showMessage($input);
            socket_write($socket, $successMessage, strlen($successMessage));
        }
    }

    /**
     * Инициализирует сокет
     * @return resource
     * @throws LogicException
     */
    private function socketInit()
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