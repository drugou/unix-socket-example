<?php declare(strict_types=1);


namespace App;

use LogicException;
use Faker\Factory;
use Throwable;

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
     * @throws Throwable
     */
    public function run(): void
    {
        $socket = $this->socketInit();
        try {
            $service = new ServerService();
            $delay = $service->getDelay();
            $regulator = new Regulator($delay);
            $messageList = [];
            while (true) {
//            if ($regulator->check()) {
//                $regulator->updateTime();
//                $message = $service->getMessage();
//                $messageList[] = $message;
//                $result = socket_write($socket, $message, 1024);
//                if ($result === false) {
//                    throw new LogicException('Не получилось отправить сообщение сервером');
//                }
//            }
                $connection = socket_accept($socket);

                if ($connection === false) {
                    throw new LogicException('Не получилось выполнить ожидание сокета');
                }

                $input = socket_read($connection, 2048);
                if ($input === false) {
                    throw new LogicException('Не получилось прочитать сообщение сервером');
                }
                var_dump($input);
//
//                if ($service->checkAnswer($input)) {
//                    // не очень классная идея хранить сообщения, лучше передавать идентификатор сообщения пользователем
////                $message = array_shift($messageList);
//                    $message = 'test';
//                    $service->showSuccessClientMessage($message);
//                }
            }
        } catch (Throwable $e) {
            socket_close($socket);
            throw $e;
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
//        $resultNoBlock = socket_set_nonblock($socket);
//        if (!$resultNoBlock) {
//            throw new LogicException('Не получилось установить не блокирующий режим');
//        }

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