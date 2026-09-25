<?php

require_once 'db.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM movies WHERE movie_id = ?");
$stmt->execute([$id]);

$movie = $stmt->fetch(PDO::FETCH_ASSOC);

$voorstellingen = [];

if ($movie) {

    $stmt = $pdo->prepare("
        SELECT *
        FROM voorstelling
        WHERE film_id = ?
        ORDER BY datum, begintijd
    ");

    $stmt->execute([$id]);

    $voorstellingen = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="nl">

<?php include 'includes/head.php'; ?>

<body>

    <?php include 'includes/header.php'; ?>


    <?php if ($movie): ?>

        <main class="mx-auto grid max-w-6xl grid-cols-1 items-center gap-10 px-6 py-12 text-white md:grid-cols-[minmax(280px,380px)_minmax(0,1fr)] md:gap-16 md:px-10">

            <!-- POSTER -->
            <div class="flex justify-center md:justify-end">

                <img
                    src="<?= htmlspecialchars($movie['poster'] ?? 'style/images/Placeholder.png') ?>"
                    alt="Poster van <?= htmlspecialchars($movie['title']) ?>"
                    class="h-auto w-full max-w-[380px] rounded-lg object-cover shadow-2xl"
                >

            </div>


            <!-- FILM INFORMATIE -->
            <section class="max-w-2xl md:mt-4">

                <h1 class="mb-5 text-4xl font-bold text-white md:text-5xl">

                    <?= htmlspecialchars($movie['title']) ?>

                </h1>


                <p class="mb-6 text-lg leading-relaxed text-gray-200">

                    <?= htmlspecialchars($movie['description'] ?? '') ?>

                </p>


                <div class="mb-8 space-y-2 text-lg text-gray-300">

                    <p>
                        <span class="font-semibold text-white">
                            Release date:
                        </span>

                        <?= htmlspecialchars($movie['release_date'] ?? '') ?>
                    </p>


                    <p>
                        <span class="font-semibold text-white">
                            Rating:
                        </span>

                        <?= htmlspecialchars($movie['imd_rating'] ?? '') ?>
                    </p>

                </div>


                <h2 class="mb-4 text-2xl font-semibold text-white">
                    Voorstellingen
                </h2>


                <div class="flex flex-wrap gap-3">

                    <?php foreach ($voorstellingen as $v): ?>

                        <a
                            href="bestellen.php?voorstelling=<?= (int) $v['voorstelling_id'] ?>"
                            class="rounded-md bg-[#67294c] px-4 py-3 text-white transition-colors hover:bg-[#813460]"
                        >

                            <?= htmlspecialchars($v['datum'] . ' ' . $v['begintijd']) ?>

                        </a>

                    <?php endforeach; ?>

                </div>

            </section>

        </main>


    <?php else: ?>

        <main class="px-6 py-12 text-center text-white">

            <h1 class="text-3xl font-bold">
                Unknown Film
            </h1>

        </main>

    <?php endif; ?>


    <?php include 'includes/footer.php'; ?>

</body>

</html>