<?php
// films komen uit de api van het hoofdkantoor

const API_URL = 'https://annex.pepijntw.com/api/v1';
const POSTER_PLACEHOLDER = 'style/images/Placeholder.png';

// de api heeft nog geen voorstellingen, dus elke film draait op deze tijden
const SHOW_TIMES = ['14:00', '17:30', '20:30'];
const SHOW_DAYS = 5;

// haalt alle pagina's op uit de api, geeft null als er iets fout gaat
function api_get(string $path): ?array
{
    // token staat in api-config.php (die staat niet in git)
    $configFile = __DIR__ . '/api-config.php';
    $token = file_exists($configFile) ? trim(require $configFile) : '';
    if ($token === '') {
        error_log('AnnexBios API: geen token. Kopieer api-config.example.php naar api-config.php en zet het token erin.');
        return null;
    }

    // staat er al een ? in het pad, dan moet page erachter met &
    $separator = str_contains($path, '?') ? '&' : '?';
    $data = [];
    $page = 1;

    // pagina voor pagina ophalen tot de laatste
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

        // geen antwoord of geen 200 = fout
        if ($body === false || $status !== 200) {
            error_log("AnnexBios API: $path (pagina $page) -> HTTP $status " . curl_error($ch));
            return null;
        }

        $json = json_decode($body, true);
        if (!is_array($json)) {
            error_log("AnnexBios API: $path (pagina $page) -> geen geldige JSON");
            return null;
        }

        // data van deze pagina bij de rest zetten
        $data = array_merge($data, $json['data'] ?? []);
        $lastPage = (int) ($json['meta']['last_page'] ?? 1);
        $page++;
    } while ($page <= $lastPage);

    return $data;
}

// film netjes maken: datum zonder tijd, rating met 1 decimaal, placeholder als er geen poster is
function api_format_movie(array $movie): array
{
    $movie['releaseDate'] = isset($movie['releaseDate']) ? substr($movie['releaseDate'], 0, 10) : null;
    $movie['imdRating'] = isset($movie['imdRating']) ? number_format((float) $movie['imdRating'], 1) : null;
    $movie['posterPath'] = !empty($movie['posterPath']) ? $movie['posterPath'] : POSTER_PLACEHOLDER;

    return $movie;
}

function api_movies(): array
{
    return array_map('api_format_movie', api_get('/movies') ?? []);
}

// 1 film zoeken op id, null als hij niet bestaat
function api_movie(int $id): ?array
{
    $movies = api_get("/movies?movieId[eq]=$id");

    return empty($movies[0]) ? null : api_format_movie($movies[0]);
}

// id = movieId + mmdd + nr van de tijd, bv. 1110022 = film 11 op 2 okt om 20:30
function api_showtimes(int $movieId): array
{
    // Nederlandse tijd, want de server staat vaak op UTC
    $tz = new DateTimeZone('Europe/Amsterdam');
    $showtimes = [];

    // elke dag vanaf vandaag, en elke tijd van die dag
    for ($day = 0; $day < SHOW_DAYS; $day++) {
        foreach (SHOW_TIMES as $nr => $time) {
            $start = new DateTime("today +$day day $time", $tz);

            // al begonnen? dan niet laten zien
            if ($start->getTimestamp() <= time()) {
                continue;
            }  

            $showtimes[] = [
                'showtimeId' => (int) ($movieId . $start->format('md') . $nr),
                'movieId'    => $movieId,
                'date'       => $start->format('d-m-Y'),
                'time'       => $start->format('H:i'),
            ];
        }
    }

    return $showtimes;
}

// laatste 5 cijfers van de id zijn datum + nr, de rest is de movieId
function api_showtime(int $id): ?array
{
    $movie = api_movie(intdiv($id, 100000));
    if (!$movie) {
        return null;
    }

    // alle voorstellingen van die film maken en de goede zoeken
    foreach (api_showtimes($movie['movieId']) as $showtime) {
        if ($showtime['showtimeId'] === $id) {
            // film erbij, bestellen.php heeft de titel nodig
            $showtime['movie'] = $movie;
            return $showtime;
        }
    }

    // niet gevonden of al begonnen
    return null;
}
