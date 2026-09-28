<?php
// Debugpagina voor admins: laat zien wat de API teruggeeft. Staat in .gitignore.
// Voorbeelden: /movies  /movies?movieId[eq]=1  /showtimes  /showtimes?showtimeId[eq]=3
require_once '../api.php';
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

$path = '/' . ltrim(trim($_GET['path'] ?? 'movies'), '/');
if (!preg_match('#^/[\w/.\-?=&\[\]%]*$#', $path)) {
    $path = '/movies';
}

$configFile = __DIR__ . '/../api-config.php';
$token = file_exists($configFile) ? trim(require $configFile) : '';

$ch = curl_init(API_URL . $path);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $token, 'Accept: application/json'],
]);
$body   = (string) curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error  = curl_error($ch);

if ($token !== '') {
    $body = str_replace($token, '[token]', $body);
}

$json = json_decode($body, true);
if (is_array($json)) {
    $body = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
?>
<title>API debug</title>
<form>
    <input name="path" value="<?= htmlspecialchars($path) ?>" size="60">
    <button>Versturen</button>
</form>
<pre>
GET <?= htmlspecialchars(API_URL . $path) ?>

Status: <?= $status ?> <?= htmlspecialchars($error) ?>


<?= htmlspecialchars($body) ?>
</pre>
