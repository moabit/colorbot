<?php


namespace ColorBot\Services;


use ColorBot\Exceptions\WeatherServiceException;

/**
 * Class WeatherService
 * @package ColorBot\Services
 */
class WeatherService
{
    protected string $token;
    protected string $city;

    public function __construct(string $token, string $city)
    {
        $this->token = $token;
        $this->city = $city;
    }

    public function getActualWeather(): array
    {
        $curl = curl_init();
        $query = http_build_query(['q' => $this->city, 'appid' => $this->token,
            'units' => 'metric', 'lang' => 'ru']);
        curl_setopt_array($curl, [
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPGET => true,
            CURLOPT_URL => 'http://api.openweathermap.org/data/2.5/weather?' . $query,
        ]);
        $res = curl_exec($curl);
        $res = json_decode($res, true);
        if (curl_error($curl) || $res['cod'] !== 200) {
            throw new WeatherServiceException('Не удалось получить актуальную погоду. Response:' . $res . 'Curl error:' . curl_error($curl));
        }
        curl_close($curl);
        return ['temp' => round($res['main']['temp']), 'description' => $res['weather'][0]['description']];
    }
}