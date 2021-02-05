<?php

require_once __DIR__ . '/vendor/autoload.php';

use ColorBot\Bot;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\ErrorHandler;

$log = new Logger('name');
$log->pushHandler(new StreamHandler(__DIR__ . '/log/log.log', Logger::WARNING));

$handler = new ErrorHandler($log);
$handler->registerErrorHandler([], true);
$handler->registerExceptionHandler([], true);
$handler->registerFatalHandler();

$builder = new \DI\ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/src/container.php');
$c = $builder->build();
$bot = $c->get(Bot::class);
$bot->run();

