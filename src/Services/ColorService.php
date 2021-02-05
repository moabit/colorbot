<?php


namespace ColorBot\Services;

use ColorBot\Exceptions\ColorServiceException;
use ColorBot\Services\Util;

/**
 * Class ColorService
 * @package ColorBot\Services
 */
class ColorService
{
    /**
     * @var array
     */
    protected array $colors;

    /**
     * ColorService constructor.
     * @throws \ColorBot\Exceptions\JSONException
     */
    public function __construct()
    {
        $this->colors = Util::readJSON(__DIR__ . '/../../store/colors_russian.json');
    }

    /**
     * @param string $srcImg
     * @return array
     * @throws ColorServiceException
     */
    public function getAverageColor(string $srcImg): array
    {
        if (!file_exists($srcImg)) {
            throw new ColorServiceException('Изображение не найдено');
        }
        $image = imagecreatefromjpeg($srcImg);
        $scaled = imagescale($image, 1, 1, IMG_BICUBIC);
        $index = imagecolorat($scaled, 0, 0);
        $rgb = imagecolorsforindex($scaled, $index);
        return $rgb;
    }

    /**
     * @param array $rgb
     * @return array
     */
    public function getNearestColor(array $rgb): array
    {
        $colors = $this->colors;
        for ($i = 0; $i < count($colors); $i++) {
            $colors[$i]['distance'] = $this->getDistance($colors[$i], $rgb);
        }
        usort($colors, function ($color1, $color2) {
            return $color1['distance'] <=> $color2['distance'];
        });
        return $colors[0];
    }

    /**
     * @param array $rgb
     */
    public function createImage(array $rgb): void
    {
        $img = imagecreatetruecolor(1000, 1000);
        $color = imagecolorallocate($img, $rgb[0], $rgb[1], $rgb[2]);
        imagefill($img, 0, 0, $color);
        imagejpeg($img, __DIR__ . '/../../store/color.jpeg', 100);
    }

    /**
     * @param array $color
     * @param array $imageColor
     * @return int
     */
    protected function getDistance(array $color, array $imageColor): int
    {
        return pow($imageColor['red'] - $color['rgb'][0], 2) + pow($imageColor['green'] - $color['rgb'][1], 2) + pow($imageColor['blue'] - $color['rgb'][2], 2);
    }
}