<?php

namespace ColorBot\Services;

use ColorBot\Exceptions\JSONException;

/**
 * Class Util
 * @package ColorBot\Services
 */
class Util
{
    /**
     * @param string $JSONpath
     * @return array
     * @throws JSONException
     */
    public static function readJSON(string $JSONpath): array
    {
        if (!file_exists($JSONpath)) {
            throw new JSONException('Файл не существует');
        }
        $fileContent = file_get_contents($JSONpath);
        $fileContent = json_decode($fileContent, true);
        if ($fileContent == null) {
            throw new JSONException('Ошибка в файле. Ошибка: ' . json_last_error_msg());
        }
        return $fileContent;
    }

    /**
     * @param array $rgb
     * @return string
     */
    public static function rgbToHex(array $rgb): string
    {
        return sprintf("#%02x%02x%02x", $rgb[0], $rgb[1], $rgb[2]);
    }
}