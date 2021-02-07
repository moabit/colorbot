<?php

use ColorBot\Services\{Util, ColorService, ImageDownloader};
use Psr\Container\ContainerInterface;
use ColorBot\Bot;

return [
    'config' => Util::readJSON(__DIR__ . '/../config.json'),
    ColorService::class => DI\create(ColorService::class),
    ImageDownloader::class => DI\factory(function (ContainerInterface $c) {
        return new ImageDownloader($c->get('config')['youtube-url'], $c->get('config')['crop-params']);
    }),
    Bot::class => DI\factory(function (ContainerInterface $c) {
        return new Bot($c->get(ColorService::class), $c->get(ImageDownloader::class), $c->get('config')['telegram-api-token'], $c->get('config')['channel-name']);
    }),
];

