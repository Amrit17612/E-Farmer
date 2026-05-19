@extends('layouts.app')
@section('title', 'Weather Forecast')
@section('page-title', 'Weather Forecast')

@push('styles')
<style>
    .weather-search { display:flex; gap:12px; margin-bottom:28px; }
    .weather-main {
        background:linear-gradient(135deg,#2E7D32,#1B5E20,#388E3C);
        border-radius:20px; padding:40px; color:#fff;
        display:grid; grid-template-columns:1fr 1fr; gap:32px;
        margin-bottom:24px; position:relative; overflow:hidden;
    }
    .weather-main::before {
        content:''; position:absolute; top:-40px; right:-40px;
        width:200px; height:200px; border-radius:50%;
        background:rgba(255,255,255,0.06);
    }
    .weather-main::after {
        content:''; position:absolute; bottom:-60px; left:-40px;
        width:240px; height:240px; border-radius:50%;
        background:rgba(255,255,255,0.04);
    }
    .weather-city { font-size:28px; font-weight:800; margin-bottom:4px; }
    .weather-date { font-size:13px; opacity:.7; margin-bottom:20px; }
    .weather-temp { font-size:72px; font-weight:800; line-height:1; }
    .weather-desc { font-size:18px; margin-top:8px; opacity:.85; text-transform:capitalize; }
    .weather-icon { font-size:80px; opacity:.85; }
    .weather-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-top:20px; }
    .w-stat { background:rgba(255,255,255,0.12); border-radius:10px; padding:12px 14px; text-align:center; }
    .w-stat .w-val { font-size:18px; font-weight:700; }
    .w-stat .w-lbl { font-size:11px; opacity:.65; margin-top:3px; }

    .forecast-grid { display:grid; grid-template-columns:repeat(7,1fr); gap:12px; }
    .forecast-card {
        background:#fff; border-radius:14px; padding:18px 12px;
        text-align:center; border:1px solid #E8F5E9;
        box-shadow:0 2px 8px rgba(46,125,50,.06); transition:.3s;
    }
    .forecast-card:hover { transform:translateY(-3px); box-shadow:0 6px 24px rgba(46,125,50,.1); }
    .forecast-day { font-size:11px; font-weight:600; color:#9E9E9E; margin-bottom:10px; }
    .forecast-icon { font-size:28px; color:#2E7D32; margin-bottom:8px; }
    .forecast-temps { font-size:13px; font-weight:600; color:#1A2E1A; }
    .forecast-temps .low { color:#9E9E9E; font-weight:400; }
    .forecast-desc { font-size:10px; color:#9E9E9E; margin-top:6px; text-transform:capitalize; }

    .advisory-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:16px; margin-top:24px; }
    .advisory-card { background:#fff; border-radius:14px; padding:20px; border:1px solid #E8F5E9; display:flex; gap:14px; align-items:flex-start; }
    .advisory-icon { width:44px; height:44px; border-radius:10px; background:#E8F5E9; color:#2E7D32; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0; }
    .advisory-card h4 { font-size:14px; font-weight:600; margin-bottom:4px; }
    .advisory-card p { font-size:12px; color:#757575; line-height:1.5; }

    @media(max-width:900px){ .weather-main{grid-template-columns:1fr;} .forecast-grid{grid-template-columns:repeat(4,1fr);} }
    @media(max-width:600px){ .forecast-grid{grid-template-columns:repeat(2,1fr);} .weather-stats{grid-template-columns:repeat(2,1fr);} }
</style>
@endpush

@section('content')
{{-- Search --}}
<form method="GET" action="{{ route('weather.search') }}">
    <div class="weather-search">
        <div class="search-bar" style="flex:1;">
            <i class="bi bi-geo-alt"></i>
            <input type="text" name="city" placeholder="Search city..." value="{{ $city }}">
        </div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
    </div>
</form>

@if($error)
    <div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> {{ $error }}</div>
@endif

@if($weather)
{{-- Current Weather --}}
<div class="weather-main">
    <div>
        <div class="weather-city"><i class="bi bi-geo-alt-fill" style="font-size:18px;"></i> {{ $weather['name'] }}</div>
        <div class="weather-date">{{ now()->format('l, d F Y') }}</div>
        <div class="weather-temp">{{ round($weather['main']['temp']) }}°C</div>
        <div class="weather-desc">{{ $weather['weather'][0]['description'] }}</div>
        <div class="weather-stats">
            <div class="w-stat">
                <div class="w-val"><i class="bi bi-droplet-fill"></i> {{ $weather['main']['humidity'] }}%</div>
                <div class="w-lbl">Humidity</div>
            </div>
            <div class="w-stat">
                <div class="w-val"><i class="bi bi-wind"></i> {{ $weather['wind']['speed'] }} km/h</div>
                <div class="w-lbl">Wind Speed</div>
            </div>
            <div class="w-stat">
                <div class="w-val"><i class="bi bi-thermometer-half"></i> {{ round($weather['main']['feels_like']) }}°C</div>
                <div class="w-lbl">Feels Like</div>
            </div>
        </div>
    </div>
    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
        <div class="weather-icon">
            @php
                $desc = strtolower($weather['weather'][0]['main'] ?? '');
                $icon = match(true) {
                    str_contains($desc,'rain')  => '🌧️',
                    str_contains($desc,'cloud') => '⛅',
                    str_contains($desc,'storm') => '⛈️',
                    str_contains($desc,'snow')  => '❄️',
                    default                     => '☀️',
                };
            @endphp
            {{ $icon }}
        </div>
        <div style="font-size:14px;opacity:.8;margin-top:12px;">{{ ucfirst($weather['weather'][0]['main']) }}</div>
        <div style="margin-top:20px;background:rgba(255,255,255,0.12);border-radius:12px;padding:14px 20px;text-align:center;">
            <div style="font-size:12px;opacity:.7;">Pressure</div>
            <div style="font-size:18px;font-weight:700;">{{ $weather['main']['pressure'] }} hPa</div>
        </div>
    </div>
</div>

{{-- 7-Day Forecast --}}
@if($forecast)
<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <span class="card-title"><i class="bi bi-calendar-week" style="color:#2E7D32;"></i> 7-Day Forecast</span>
    </div>
    <div class="card-body">
        <div class="forecast-grid">
            @foreach($forecast as $day)
            <div class="forecast-card">
                <div class="forecast-day">{{ $day['day'] }}</div>
                <div class="forecast-icon">
                    @php
                        $di = strtolower($day['description']);
                        echo match(true) {
                            str_contains($di,'rain')  => '🌧️',
                            str_contains($di,'cloud') => '☁️',
                            str_contains($di,'storm') => '⛈️',
                            str_contains($di,'snow')  => '❄️',
                            default                   => '☀️',
                        };
                    @endphp
                </div>
                <div class="forecast-temps">
                    {{ round($day['temp_max']) }}° <span class="low">/ {{ round($day['temp_min']) }}°</span>
                </div>
                <div class="forecast-desc">{{ Str::limit($day['description'], 15) }}</div>
                <div style="font-size:10px;color:#9E9E9E;margin-top:4px;">
                    <i class="bi bi-droplet" style="color:#66BB6A;"></i> {{ $day['humidity'] }}%
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- Farm Advisories --}}
<div style="margin-bottom:8px;">
    <h3 style="font-size:16px;font-weight:700;margin-bottom:4px;">Farm Advisories</h3>
    <p style="font-size:13px;color:#757575;">Based on current weather conditions for your farm</p>
</div>
<div class="advisory-grid">
    <div class="advisory-card">
        <div class="advisory-icon"><i class="bi bi-droplet-fill"></i></div>
        <div>
            <h4>Irrigation</h4>
            <p>{{ $weather['main']['humidity'] > 70 ? 'Humidity is high. Reduce irrigation frequency to prevent waterlogging.' : 'Moderate humidity. Maintain regular irrigation schedule.' }}</p>
        </div>
    </div>
    <div class="advisory-card">
        <div class="advisory-icon"><i class="bi bi-bug-fill" style="color:#F57F17;"></i></div>
        <div>
            <h4>Pest Alert</h4>
            <p>{{ $weather['main']['humidity'] > 65 ? 'High humidity increases risk of fungal diseases. Monitor crops closely and consider preventive spraying.' : 'Weather conditions are favorable. Continue standard pest management.' }}</p>
        </div>
    </div>
    <div class="advisory-card">
        <div class="advisory-icon"><i class="bi bi-sun-fill" style="color:#F57F17;"></i></div>
        <div>
            <h4>Sowing Conditions</h4>
            <p>{{ $weather['main']['temp'] > 35 ? 'High temperature. Avoid sowing in afternoon hours. Early morning sowing recommended.' : 'Temperature is suitable for most crop varieties.' }}</p>
        </div>
    </div>
    <div class="advisory-card">
        <div class="advisory-icon"><i class="bi bi-wind"></i></div>
        <div>
            <h4>Spraying</h4>
            <p>{{ $weather['wind']['speed'] > 15 ? 'High wind speed detected. Avoid pesticide/fertilizer spraying today to prevent spray drift.' : 'Wind speed is suitable for spraying operations.' }}</p>
        </div>
    </div>
</div>
@endif
@endsection
