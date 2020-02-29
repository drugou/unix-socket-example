<?php declare(strict_types=1);


namespace App;

use LogicException;
use Faker\Factory;

/**
 * Class Server
 *
 * Сервер
 *
 * @package App
 */
class Server
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
     * Запускает сервер
     * @throws LogicException
     */
    public function run(): void
    {
        $socket = $this->socketInit();
        $service = new ServerService();
        $delay = $service->getDelay();
        $regulator = new Regulator($delay);
        $messageList = [];
        while (true) {
            if ($regulator->check()) {
                $regulator->updateTime();
                $message = $service->getMessage();
                $messageList[] = $message;
                socket_write($socket, $message);
            }

            $input = socket_read($socket, 1024, PHP_NORMAL_READ);
            if ($input === false) {
                throw new LogicException('Не получилось прочитать сообщение сервером');
            }

            if ($service->checkAnswer($input)) {
                // не очень классная идея хранить сообщения, лучше передавать идентификатор сообщения пользователем
                $message = array_shift($messageList);
                $service->showSuccessClientMessage($message);
            }
        }
    }

    /**
     * Инициализирует сокет
     * @return resource
     * @throws LogicException
     */
    private function socketInit()
    {
        $this->dropPreviousSocketAddress();
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

        return $socket;
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

}