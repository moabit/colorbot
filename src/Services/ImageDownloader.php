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
     * @var array
     */
    protected array $cropParams;

    /**
     * ImageDownloader constructor.
     * @param string $url
     * @param array $cropParams
     */
    public function __construct(string $url, array $cropParams)
    {
        $this->url = $url;
        $this->cropParams = $cropParams;
    }

    /**
     * @throws ImageDownloaderException
     */
    public function getSourceImage(): void
    {
        $path = __DIR__ . '/../../storage/src_img.jpeg';
        $output = null;
        $code = null;
        $res = exec('ffmpeg -ss 00:00:01 -i $(youtube-dl --get-url ' . $this->url . ') -vframes 1 -q:v 2 -y ' . $path, $output, $code);
        if ($code !== 0) {
            throw new ImageDownloaderException('Не удалось скачать изображение. Code: ' . $code . 'Output:' . $output);
        }
       // $image = imagecreatefromjpeg($path);
      //  $image = imagecrop($image, ['x' => 1000, 'y' => 0, 'width' => 900, 'height' => 800]);
     //   imagejpeg($image, $path, 100);
        if (!file_exists($path)) {
            throw new ImageDownloaderException('Изображение не найдено');
        }


    }
}