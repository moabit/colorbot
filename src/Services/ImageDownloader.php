<?php


namespace ColorBot\Services;

use ColorBot\Exceptions\ImageDownloaderException;

/**
 * Class ImageDownloader
 * @package ColorBot\Services
 */
class ImageDownloader
{
    /**
     * @var string
     */
    protected string $url;

    /**
     * ImageDownloader constructor.
     * @param string $url

     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @throws ImageDownloaderException
     */
    public function getSourceImage(): void
    {
        $path = __DIR__ . '/../../storage/src_img.jpeg';
        $output = null;
        $code = null;
        exec('ffmpeg -ss 00:00:01 -i $(youtube-dl --get-url ' . $this->url . ') -vframes 1 -q:v 2 -y ' . $path, $output, $code);
        if ($code !== 0) {
            throw new ImageDownloaderException('Не удалось скачать изображение. Code: ' . $code . 'Output:' . $output);
        }
        if (!file_exists($path)) {
            throw new ImageDownloaderException('Изображение не найдено');
        }

    }
}