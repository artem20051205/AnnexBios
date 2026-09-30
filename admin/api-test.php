<?php
// debug pagina voor admin, laat zien wat de api teruggeeft
// bv. /movies  /movies?movieId[eq]=1  /showtimes  /showtimes?showtimeId[eq]=3
require_once '../api.php';
session_start();

// niet ingelogd? dan terug naar de login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// pad uit de url, alleen normale tekens mogen, anders gewoon /movies
$path = '/' . ltrim(trim($_GET['path'] ?? 'movies'), '/');
if (!preg_match('#^/[\w/.\-?=&\[\]%]*$#', $path)) {
    $path = '/movies';
}

// zelfde token als in api.php
$configFile = __DIR__ . '/../api-config.php';
$token = file_exists($configFile) ? trim(require $configFile) : '';

// maar 1 pagina ophalen, hier willen we het echte antwoord van de api zien
$ch = curl_init(API_URL . $path);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $token, 'Accept: application/json'],
]);
$body   = (string) curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error  = curl_error($ch);

// token niet laten zien op de pagina
if ($token !== '') {
    $body = str_replace($token, '[token]', $body);
}

// json mooi maken met enters en spaties
$json = json_decode($body, true);
if (is_array($json)) {
    $body = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API debug</title>
    <link rel="icon" type="image/png" href="../style/images/favicon.png">
    <link rel="stylesheet" href="../style/output.css?v=<?= filemtime(__DIR__ . '/../style/output.css') ?>">
    <link rel="stylesheet" href="../style/style.css?v=<?= filemtime(__DIR__ . '/../style/style.css') ?>">
</head>
<body>
<?php $base = '../'; include '../includes/header.php'; ?>

<main class="chairs">
<h1>API debug</h1>
<form>
    <div class="customer">
        <input name="path" value="<?= htmlspecialchars($path) ?>" size="60">
    </div>
    <button class="details-button">Versturen</button>
</form>
<pre style="text-align: left; overflow: auto;">
GET <?= htmlspecialchars(API_URL . $path) ?>

Status: <?= $status ?> <?= htmlspecialchars($error) ?>


<?= htmlspecialchars($body) ?>
</pre>
<a class="details-button" href="admin.php">Terug</a>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
