<?php
// films en tijden komen uit de api van het hoofdkantoor

const API_URL = 'https://annex.pepijntw.com/api/v1';
const POSTER_PLACEHOLDER = 'style/images/Placeholder.png';

// haalt alle pagina's op uit de api, geeft null als er iets fout gaat
function api_get(string $path): ?array
{
    $configFile = __DIR__ . '/api-config.php';
    $token = file_exists($configFile) ? trim(require $configFile) : '';
    if ($token === '') {
        error_log('AnnexBios API: geen token. Kopieer api-config.example.php naar api-config.php en zet het token erin.');
        return null;
    }

    $separator = str_contains($path, '?') ? '&' : '?';
    $data = [];
    $page = 1;

    do {
        $ch = curl_init(API_URL . $path . $separator . 'page=' . $page);
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
            error_log("AnnexBios API: $path (pagina $page) -> HTTP $status " . curl_error($ch));
            return null;
        }

        $json = json_decode($body, true);
        if (!is_array($json)) {
            error_log("AnnexBios API: $path (pagina $page) -> geen geldige JSON");
            return null;
        }

        $data = array_merge($data, $json['data'] ?? []);
        $lastPage = (int) ($json['meta']['last_page'] ?? 1);
        $page++;
    } while ($page <= $lastPage);

    return $data;
}

function api_format_movie(array $movie): array
{
    $movie['releaseDate'] = isset($movie['releaseDate']) ? substr($movie['releaseDate'], 0, 10) : null;
    $movie['imdRating'] = isset($movie['imdRating']) ? number_format((float) $movie['imdRating'], 1) : null;
    $movie['posterPath'] = !empty($movie['posterPath']) ? $movie['posterPath'] : POSTER_PLACEHOLDER;

    return $movie;
}

// tijd uit de api is in UTC, omzetten naar Nederlandse tijd
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

// alleen voorstellingen die nog moeten komen, gesorteerd op tijd
function api_showtimes(int $movieId): array
{
    $showtimes = api_get("/showtimes?movieId[eq]=$movieId") ?? [];
    $showtimes = array_filter($showtimes, fn($s) => strtotime($s['startTime']) > time());
    usort($showtimes, fn($a, $b) => strcmp($a['startTime'], $b['startTime']));

    return array_map('api_format_showtime', $showtimes);
}

function api_showtime(int $id): ?array
{
    $showtimes = api_get("/showtimes?showtimeId[eq]=$id");

    return empty($showtimes[0]) ? null : api_format_showtime($showtimes[0]);
}
