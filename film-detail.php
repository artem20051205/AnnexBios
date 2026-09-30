<?php
require_once 'api.php';

// id van de film uit de url
$id = (int) ($_GET['id'] ?? 0);

$movie = api_movie($id);
// alleen tijden maken als de film echt bestaat
$voorstellingen = $movie ? api_showtimes($id) : [];
?>

<!DOCTYPE html>
<html lang="nl">

<?php include 'includes/head.php'; ?>

<body>

    <?php include 'includes/header.php'; ?>


    <?php if ($movie): ?>

        <main class="detail">

            <!-- poster, laadt hij niet dan komt de placeholder -->
            <div class="detail-poster">

                <img
                    src="<?= htmlspecialchars($movie['posterPath']) ?>"
                    onerror="this.onerror=null; this.src='style/images/Placeholder.png'"
                    alt="Poster van <?= htmlspecialchars($movie['title']) ?>"
                >

            </div>


            <!-- info over de film -->
            <section class="detail-info">

                <h1 class="detail-title">

                    <?= htmlspecialchars($movie['title']) ?>

                </h1>


                <p class="detail-description">

                    <?= htmlspecialchars($movie['description'] ?? '') ?>

                </p>


                <div class="detail-meta">

                    <p>
                        <span>
                            Release date:
                        </span>

                        <?= htmlspecialchars($movie['releaseDate'] ?? '-') ?>
                    </p>


                    <p>
                        <span>
                            Rating:
                        </span>

                        <?= htmlspecialchars($movie['imdRating'] ?? '-') ?>
                    </p>

                </div>


                <h2 class="detail-subtitle">
                    Voorstellingen
                </h2>


                <?php if (!$voorstellingen): ?>
                    <p class="detail-empty">Er zijn nog geen voorstellingen gepland.</p>
                <?php endif; ?>

                <!-- elke tijd is een link naar bestellen.php met de id van de voorstelling -->
                <div class="showtimes">

                    <?php foreach ($voorstellingen as $v): ?>

                        <a href="bestellen.php?voorstelling=<?= (int) $v['showtimeId'] ?>">

                            <?= htmlspecialchars($v['date'] . ' ' . $v['time']) ?>

                        </a>

                    <?php endforeach; ?>

                </div>

            </section>

        </main>


    <?php else: ?>

        <!-- geen film met dit id -->
        <main class="detail-unknown">

            <h1>
                Unknown Film
            </h1>

        </main>

    <?php endif; ?>


    <?php include 'includes/footer.php'; ?>

</body>

</html>