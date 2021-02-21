<?php

use ColorBot\Services\{Util, ColorService, ImageDownloader, WeatherService};
use Psr\Container\ContainerInterface;
use ColorBot\Bot;

return [
    'config' => Util::readJSON(__DIR__ . '/../config.json'),
    ColorService::class => DI\create(ColorService::class),
    ImageDownloader::class => DI\factory(function (ContainerInterface $c) {
        return new ImageDownloader($c->get('config')['youtube-url']);
    }),
    WeatherService::class=>DI\factory(function (ContainerInterface $c) {
        return new WeatherService($c->get('config')['openweather-api-token'],$c->get('config')['city']);
    }),
    Bot::class => DI\factory(function (ContainerInterface $c) {
        return new Bot($c->get(ColorService::class), $c->get(ImageDownloader::class),$c->get(WeatherService::class), $c->get('config')['telegram-api-token'], $c->get('config')['channel-name']);
    }),
];

