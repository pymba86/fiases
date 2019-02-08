<?php

namespace Library\Handlers;

use Monolog\Logger;
use Phalcon\Config;

use function memory_get_usage;
use function microtime;
use function number_format;


class ErrorHandler
{

    /** @var Config; */
    private $config;

    /** @var Logger */
    private $logger;

    /**
     * ErrorHandler constructor.
     *
     * @param Config $config
     * @param Logger $logger
     */
    public function __construct(Logger $logger, Config $config)
    {
        $this->config = $config;
        $this->logger = $logger;
    }


    /**
     * Обработать ошибку
     * @param int $number
     * @param string $message
     * @param string $file
     * @param int $line
     */
    public function handle(int $number, string $message, string $file, int $line)
    {
        $this
            ->logger
            ->error(
                sprintf(
                    '[#:%s]-[L: %s] : %s (%s)',
                    $number,
                    $line,
                    $message,
                    $file
                )
            );
    }

    /**
     * Заверщение работы приложения - Сбор метрики в режиме разработчика
     */
    public function shutdown()
    {
        if ($this->config->path('app.devMode')) {
            $memory = number_format(memory_get_usage() / 1000000, 2);
            $execution = number_format(
                microtime(true) - $this->config->path('app.time'),
                4
            );

            $this
                ->logger
                ->info(
                    sprintf(
                        'Shutdown completed [%s]s - [%s]MB',
                        $execution,
                        $memory
                    )
                );
        }
    }


}
