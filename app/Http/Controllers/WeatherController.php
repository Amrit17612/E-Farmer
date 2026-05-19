<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    private string $apiKey;
    private string $baseUrl = 'https://api.openweathermap.org/data/2.5';

    public function __construct()
    {
        $this->apiKey = config('services.openweather.key', env('OPENWEATHER_API_KEY', ''));
    }

    public function index(Request $request)
    {
        $city    = $request->get('city', 'New Delhi');
        $weather = null;
        $forecast = null;
        $error   = null;

        // Only try live API if we have a real-looking API key
        $isPlaceholder = ($this->apiKey === 'your_openweather_api_key' || empty($this->apiKey));

        if (!$isPlaceholder) {
            try {
                $weatherResponse = Http::get("{$this->baseUrl}/weather", [
                    'q'     => $city,
                    'appid' => $this->apiKey,
                    'units' => 'metric',
                ]);

                $forecastResponse = Http::get("{$this->baseUrl}/forecast", [
                    'q'     => $city,
                    'appid' => $this->apiKey,
                    'units' => 'metric',
                    'cnt'   => 40,
                ]);

                if ($weatherResponse->successful()) {
                    $weather  = $weatherResponse->json();
                    $forecast = $this->parseForecast($forecastResponse->json());
                } else {
                    $error = 'City not found or API key invalid. Please check your configuration.';
                    // Fallback to demo data on failure so the page still looks good
                    $weather  = $this->demoWeatherData($city);
                    $forecast = $this->demoForecastData();
                }
            } catch (\Exception $e) {
                $error = 'Weather service unavailable. Showing demo data.';
                $weather  = $this->demoWeatherData($city);
                $forecast = $this->demoForecastData();
            }
        } else {
            // Demo weather data when no API key
            $weather  = $this->demoWeatherData($city);
            $forecast = $this->demoForecastData();
        }

        return view('weather.index', compact('weather', 'forecast', 'error', 'city'));
    }

    public function search(Request $request)
    {
        return $this->index($request);
    }

    private function parseForecast(array $data): array
    {
        $daily = [];
        if (!isset($data['list'])) return [];

        foreach ($data['list'] as $item) {
            $date = date('Y-m-d', $item['dt']);
            if (!isset($daily[$date])) {
                $daily[$date] = [
                    'date'        => $date,
                    'day'         => date('D, d M', $item['dt']),
                    'temp_max'    => $item['main']['temp_max'],
                    'temp_min'    => $item['main']['temp_min'],
                    'description' => $item['weather'][0]['description'],
                    'icon'        => $item['weather'][0]['icon'],
                    'humidity'    => $item['main']['humidity'],
                    'wind_speed'  => $item['wind']['speed'],
                ];
            } else {
                if ($item['main']['temp_max'] > $daily[$date]['temp_max']) {
                    $daily[$date]['temp_max'] = $item['main']['temp_max'];
                }
                if ($item['main']['temp_min'] < $daily[$date]['temp_min']) {
                    $daily[$date]['temp_min'] = $item['main']['temp_min'];
                }
            }
        }
        return array_slice(array_values($daily), 0, 7);
    }

    private function demoWeatherData(string $city): array
    {
        return [
            'name' => $city,
            'main' => ['temp' => 28, 'feels_like' => 30, 'humidity' => 65, 'pressure' => 1012],
            'weather' => [['description' => 'Partly Cloudy', 'icon' => '02d', 'main' => 'Clouds']],
            'wind' => ['speed' => 12],
            'visibility' => 10000,
            'sys' => ['sunrise' => strtotime('06:00'), 'sunset' => strtotime('18:30')],
        ];
    }

    private function demoForecastData(): array
    {
        $days   = [];
        $icons  = ['01d', '02d', '03d', '10d', '04d'];
        $descs  = ['Sunny', 'Partly Cloudy', 'Cloudy', 'Light Rain', 'Overcast'];
        for ($i = 1; $i <= 7; $i++) {
            $idx    = ($i - 1) % 5;
            $days[] = [
                'day'         => date('D, d M', strtotime("+{$i} day")),
                'temp_max'    => rand(28, 36),
                'temp_min'    => rand(18, 25),
                'description' => $descs[$idx],
                'icon'        => $icons[$idx],
                'humidity'    => rand(50, 80),
                'wind_speed'  => rand(8, 20),
            ];
        }
        return $days;
    }
}
