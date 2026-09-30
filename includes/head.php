<!-- alleen voor pagina's in de hoofdmap, de admin pagina's hebben een eigen head -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AnnexBios Bilthoven</title>
    <link rel="icon" type="image/png" href="style/images/favicon.png">
    <!-- ?v= is de tijd van de laatste wijziging (filemtime), zodat de browser niet de oude css uit de cache pakt -->
    <link rel="stylesheet" href="style/output.css?v=<?= filemtime(__DIR__ . '/../style/output.css') ?>">
    <link rel="stylesheet" href="style/style.css?v=<?= filemtime(__DIR__ . '/../style/style.css') ?>">
</head>
