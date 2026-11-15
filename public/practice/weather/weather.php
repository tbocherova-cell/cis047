<?php
// Request JSON data from an API.
function getApiJson(string $url): ?array
{
    $ch = curl_init($url);

    if ($ch === false) {
        return null;
    }

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 10
    ]);

    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false || $status !== 200) {
        return null;
    }

    $data = json_decode($response, true);

    return is_array($data) ? $data : null;
}

// Escape text before displaying it in HTML.
function escapeHtml(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

$error = '';
$locationLabel = '';
$temperature = null;
$humidity = null;
$windSpeed = null;
$temperatureSymbol = '';
$windUnit = '';
$weatherTime = '';
$weatherTimezone = '';

// STEP 1: Read and validate the form.
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    $error = 'Please start with the weather lookup form.';
} else {
    $city = $_POST['city'] ?? '';
    $units = $_POST['units'] ?? 'fahrenheit';

    if (
        !is_string($city) ||
        strlen(trim($city)) < 2 ||
        strlen($city) > 100
    ) {
        http_response_code(400);
        $error = 'Please enter a city name between 2 and 100 bytes.';
    } elseif (
        !is_string($units) ||
        !in_array($units, ['fahrenheit', 'celsius'], true)
    ) {
        http_response_code(400);
        $error = 'Please choose Fahrenheit or Celsius.';
    } else {
        $city = trim($city);
        $temperatureSymbol = $units === 'celsius' ? '°C' : '°F';
        $windUnit = $units === 'celsius' ? 'km/h' : 'mph';

        // STEP 2: Find the city's coordinates.
        $geocodingUrl =
            'https://geocoding-api.open-meteo.com/v1/search?' .
            http_build_query([
                'name' => $city,
                'count' => 1,
                'language' => 'en',
                'format' => 'json'
            ]);

        $geocodingData = getApiJson($geocodingUrl);
        $place = $geocodingData['results'][0] ?? null;

        if ($geocodingData === null) {
            $error = 'The location service is unavailable. Please try again later.';
        } elseif (
            !is_array($place) ||
            !is_numeric($place['latitude'] ?? null) ||
            !is_numeric($place['longitude'] ?? null)
        ) {
            $error = 'We could not find that city. Check the spelling or try another city name.';
        } else {
            // STEP 3: Request weather in the city's local time zone.
            $weatherUrl =
                'https://api.open-meteo.com/v1/forecast?' .
                http_build_query([
                    'latitude' => (float) $place['latitude'],
                    'longitude' => (float) $place['longitude'],
                    'current' =>
                        'temperature_2m,relative_humidity_2m,wind_speed_10m',
                    'temperature_unit' => $units,
                    'timezone' => 'auto',
                    'wind_speed_unit' =>
                        $units === 'celsius' ? 'kmh' : 'mph'
                ]);

            $weatherData = getApiJson($weatherUrl);
            $current = $weatherData['current'] ?? null;

            if (
                !is_array($current) ||
                !is_numeric($current['temperature_2m'] ?? null) ||
                !is_numeric($current['relative_humidity_2m'] ?? null) ||
                !is_numeric($current['wind_speed_10m'] ?? null)
            ) {
                $error = 'Weather data is unavailable. Please try again later.';
            } else {
                $temperature = (float) $current['temperature_2m'];
                $humidity = (float) $current['relative_humidity_2m'];
                $windSpeed = (float) $current['wind_speed_10m'];

                // Read the weather timestamp returned by the API.
                if (is_string($current['time'] ?? null)) {
                    $weatherTime = str_replace(
                        'T',
                        ' · ',
                        $current['time']
                    );
                }

                if (is_string($weatherData['timezone'] ?? null)) {
                    $weatherTimezone = $weatherData['timezone'];
                }

                // Show the location actually matched by the API.
                $locationLabel = implode(', ', array_filter([
                    $place['name'] ?? $city,
                    $place['admin1'] ?? '',
                    $place['country'] ?? ''
                ]));
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Result</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 48px 24px;
            background: #faf6f1;
            color: #173f46;
            font-family: Georgia, serif;
            font-size: 20px;
            line-height: 1.6;
        }

        main {
            max-width: 640px;
            margin: 0 auto;
            padding: 32px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 8px 24px rgb(23 63 70 / 10%);
        }

        h1 {
            margin-top: 0;
            margin-bottom: 16px;
            line-height: 1.2;
        }

        .location {
            margin-top: 0;
            margin-bottom: 16px;
        }

        .weather-time {
            margin: 0 0 24px;
            padding: 12px 16px;
            border-radius: 10px;
            background: #f0f5f4;
            font-size: 16px;
            overflow-wrap: anywhere;
        }

        dl {
            margin: 0;
        }

        .weather-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 8px 24px;
            padding: 16px 0;
            border-bottom: 1px solid #d7e0df;
        }

        dd {
            margin: 0;
            font-weight: bold;
        }

        .error {
            padding: 16px;
            background: #fff0eb;
            border-left: 4px solid #9c4f35;
        }

        a {
            color: #173f46;
        }

        .back-link {
            display: inline-block;
            margin-top: 28px;
            padding: 12px 20px;
            border-radius: 10px;
            background: #173f46;
            color: #ffffff;
            text-decoration: none;
        }

        .back-link:hover {
            background: #9c4f35;
        }

        a:focus-visible {
            outline: 3px solid #f2b705;
            outline-offset: 4px;
        }

        footer {
            margin-top: 28px;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <main>
        <?php if ($error !== ''): ?>
            <h1>Weather Lookup</h1>

            <p class="error" role="alert">
                <?php echo escapeHtml($error); ?>
            </p>
        <?php else: ?>
            <!-- STEP 4: Display validated results safely. -->
            <h1>Current Weather</h1>

            <p class="location">
                <?php echo escapeHtml($locationLabel); ?>
            </p>

            <?php if ($weatherTime !== ''): ?>
                <p class="weather-time">
                    <strong>Weather data for:</strong>
                    <?php echo escapeHtml($weatherTime); ?>

                    <?php if ($weatherTimezone !== ''): ?>
                        <br>
                        Local time zone:
                        <?php echo escapeHtml($weatherTimezone); ?>
                    <?php endif; ?>
                </p>
            <?php endif; ?>

            <dl>
                <div class="weather-row">
                    <dt>Temperature</dt>
                    <dd>
                        <?php echo number_format($temperature, 1); ?>
                        <?php echo escapeHtml($temperatureSymbol); ?>
                    </dd>
                </div>

                <div class="weather-row">
                    <dt>Relative humidity</dt>
                    <dd>
                        <?php echo number_format($humidity, 0); ?>%
                    </dd>
                </div>

                <div class="weather-row">
                    <dt>Wind speed</dt>
                    <dd>
                        <?php echo number_format($windSpeed, 1); ?>
                        <?php echo escapeHtml($windUnit); ?>
                    </dd>
                </div>
            </dl>
        <?php endif; ?>

        <a class="back-link" href="weather.html">
            Search another city
        </a>

        <footer>
            Weather data by
            <a href="https://open-meteo.com/">Open-Meteo.com</a>
            (<a href="https://creativecommons.org/licenses/by/4.0/">CC BY 4.0</a>).
            Location information based on GeoNames.
        </footer>
    </main>
</body>
</html>