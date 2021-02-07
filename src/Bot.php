<?php

namespace ColorBot;

use ColorBot\Exceptions\PostTelegramException;
use ColorBot\Services\ColorService;
use ColorBot\Services\ImageDownloader;
use ColorBot\Services\Util;

/**
 * Class Bot
 * @package ColorBot
 */
class Bot
{
    /**
     * @var ColorService
     */
    protected ColorService $colorService;
    /**
     * @var ImageDownloader
     */
    protected ImageDownloader $imageDownloader;
    /**
     * @var string
     */
    protected string $tgToken;
    /**
     * @var string
     */
    protected string $channelName;

    /**
     * Bot constructor.
     * @param ColorService $colorService
     * @param ImageDownloader $imageDownloader
     * @param string $tgToken
     * @param string $channelName
     */
    public function __construct(ColorService $colorService, ImageDownloader $imageDownloader, string $tgToken, string $channelName)
    {
        $this->colorService = $colorService;
        $this->imageDownloader = $imageDownloader;
        $this->tgToken = $tgToken;
        $this->channelName = $channelName;
    }

    /**
     * @throws Exceptions\ColorServiceException
     * @throws Exceptions\ImageDownloaderException
     * @throws PostTelegramException
     */
    public function run(): void
    {

        //get actual color
        $this->imageDownloader->getSourceImage();
        $actualColor = $this->colorService->getNearestColor($this->colorService->getAverageColor(__DIR__ . '/../storage/src_img.jpeg'));
        $name = $actualColor['name'];
        //do not post if the sky hasn't changed its color
        $pathToOldColor = __DIR__ . '/../storage/color.jpeg';
        $previousColor = file_exists($pathToOldColor) ? $this->colorService->getNearestColor($this->colorService->getAverageColor($pathToOldColor)) : null;
        if ($previousColor['name'] == $name) {
            exit;
        }
        //post to tg
        $hexColor = Util::rgbToHex($actualColor['rgb']);
        $this->colorService->createImage($actualColor['rgb']);
        $message = 'Сейчас небо в Петербурге такого цвета: ' . $name . ' ' . $hexColor;
        $this->postTelegram($message);

    }

    /**
     * @param $message
     * @throws PostTelegramException
     */
    protected function postTelegram($message)
    {
        $cfile = new \CURLFile(__DIR__ . '/../storage/color.jpeg', 'image/jpeg');
        $data = ['chat_id' => $this->channelName, 'photo' => $cfile, 'caption' => $message];
        $curl = curl_init();
        curl_setopt_array($curl, [CURLOPT_POST => true,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => 'https://api.telegram.org/bot' . $this->tgToken . '/sendPhoto',
            CURLOPT_POSTFIELDS => $data]);
        $res = curl_exec($curl);
        if (curl_error($curl) || json_decode($res, true)['ok'] == false) {
            throw new PostTelegramException('Не удалось отправить пост в канал. Response:'. $res . 'Curl error:'.curl_error($curl));
        }
        curl_close($curl);
    }
}