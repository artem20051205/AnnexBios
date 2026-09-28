<?php
// Films en voorstellingen komen uit de API van het hoofdkantoor.

const API_URL = 'https://annex.pepijntw.com/api/v1';
const POSTER_PLACEHOLDER = 'style/images/Placeholder.png';

// GET-verzoek naar de API. Geeft "data" terug, of null als er iets misgaat.
function api_get(string $path): ?array
{
    $configFile = __DIR__ . '/api-config.php';
    $token = file_exists($configFile) ? trim(require $configFile) : '';
    if ($token === '') {
        error_log('AnnexBios API: geen token in api-config.php');
        return null;
    }

    $ch = curl_init(API_URL . $path);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 5,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $token,
            'Accept: application/json',
        ],
    ]);

    $body   = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($body === false || $status !== 200) {
        error_log("AnnexBios API: $path -> HTTP $status " . curl_error($ch));
        return null;
    }

    $json = json_decode($body, true);
    if (!is_array($json)) {
        error_log("AnnexBios API: $path -> geen geldige JSON");
        return null;
    }

    return $json['data'] ?? $json;
}

function api_format_movie(array $movie): array
{
    $movie['releaseDate'] = isset($movie['releaseDate']) ? substr($movie['releaseDate'], 0, 10) : null;
    $movie['imdRating'] = isset($movie['imdRating']) ? number_format((float) $movie['imdRating'], 1) : null;
    $movie['posterPath'] = !empty($movie['posterPath']) ? $movie['posterPath'] : POSTER_PLACEHOLDER;

    return $movie;
}

// startTime is in UTC; we tonen de Nederlandse tijd
function api_format_showtime(array $showtime): array
{
    $start = new DateTime($showtime['startTime']);
    $start->setTimezone(new DateTimeZone('Europe/Amsterdam'));

    $showtime['date'] = $start->format('d-m-Y');
    $showtime['time'] = $start->format('H:i');

    return $showtime;
}

function api_movies(): array
{
    return array_map('api_format_movie', api_get('/movies') ?? []);
}

function api_movie(int $id): ?array
{
    $movies = api_get("/movies?movieId[eq]=$id");

    return empty($movies[0]) ? null : api_format_movie($movies[0]);
}

// Alleen toekomstige voorstellingen van onze bioscoop, gesorteerd op tijd
function api_showtimes(int $movieId): array
{
    $showtimes = api_get("/showtimes?movieId[eq]=$movieId") ?? [];
    $showtimes = array_filter($showtimes, fn($s) => strtotime($s['startTime']) > time());
    usort($showtimes, fn($a, $b) => strcmp($a['startTime'], $b['startTime']));

    return array_map('api_format_showtime', $showtimes);
}

// Geeft null als de voorstelling niet bij onze bioscoop hoort
function api_showtime(int $id): ?array
{
    $showtimes = api_get("/showtimes?showtimeId[eq]=$id");

    return empty($showtimes[0]) ? null : api_format_showtime($showtimes[0]);
}
