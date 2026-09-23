<?php

$films = [
    [
        'title' => 'Dune: Part Two',
        'description' => 'Paul Atreides reist verder in een epische strijd om de toekomst van zijn familie en de planeet Arrakis.',
        'release_date' => '2024-02-29',
        'rating' => 8.6,
    ],
    [
        'title' => 'The Batman',
        'description' => 'Batman onderzoekt een reeks moorden in Gotham en komt dichter bij de waarheid over de stad.',
        'release_date' => '2022-03-04',
        'rating' => 7.8,
    ],
    [
        'title' => 'Oppenheimer',
        'description' => 'Het verhaal van J. Robert Oppenheimer en zijn rol bij de ontwikkeling van de atoombom.',
        'release_date' => '2023-07-21',
        'rating' => 8.4,
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies</title>
</head>
<body>
<?php foreach ($films as $film): ?>
      <div>
        <h2><?= htmlspecialchars($film['title']) ?></h2>

        <p>
            <?= htmlspecialchars($film['description']) ?>
        </p>

        <p>
            release date:
            <?= htmlspecialchars($film['release_date']) ?>
        </p>

        <p>
            Rating:
            <?= htmlspecialchars((string) $film['rating']) ?>
        </p>

        <hr>
    </div>

<?php endforeach; ?>
        
</body>
</html>