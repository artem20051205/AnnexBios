<?php
require_once 'db.php';

$stmt = $pdo->query("SELECT * FROM movies ORDER BY movie_id");
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">

<?php include 'includes/head.php'; ?>

<body>

    <?php include 'includes/header.php'; ?>


    <!-- DUNE AFBEELDING -->
    <div class="dunepic">

        <img
            src="style/images/dune.png"
            alt="Dune"
            class="dune"
        >

        <!-- TEKST OP AFBEELDING -->
        <div class="film-intro">

            <h2>Welkom bij AnnexBios Bilthoven</h2>

            <p>Ontdek de laatste films in comfort</p>

            <button onclick="window.location.href='films.php'">
                Film Agenda Bekijken
            </button>

        </div>

    </div>


    <!-- FILMS -->
    <div class="arraybackground">

        <h2 class="movies-title">
            NU DRAAIEND
        </h2>


        <!-- SLIDER -->
        <div class="movie-slider">

            <!-- LINKER PIJL -->
            <button
                class="slider-arrow arrow-left"
                onclick="scrollMovies(-1)"
            >
                &#10094;
            </button>


            <!-- FILMS -->
            <div class="movies-scroll" id="moviesScroll">

                <?php foreach ($movies as $movie): ?>

                    <div class="movie-item">

                        <img
                            class="movie-poster"
                            src="<?= htmlspecialchars(
                                !empty($movie['poster'])
                                    ? $movie['poster']
                                    : 'style/images/Placeholder.png'
                            ) ?>"
                            alt="<?= htmlspecialchars($movie['title']) ?>"
                        >


                        <a
                            class="details-button"
                            href="film-detail.php?id=<?= htmlspecialchars($movie['movie_id']) ?>"
                        >
                            BEKIJKEN
                        </a>


                        <h2>
                            <?= htmlspecialchars($movie['title']) ?>
                        </h2>


                        <p>
                            Rating:
                            <?= htmlspecialchars($movie['imd_rating'] ?? '') ?>
                        </p>


                        <p>
                            <?= htmlspecialchars($movie['release_date'] ?? '') ?>
                        </p>

                    </div>

                <?php endforeach; ?>

            </div>


            <!-- RECHTER PIJL -->
            <button
                class="slider-arrow arrow-right"
                onclick="scrollMovies(1)"
            >
                &#10095;
            </button>

        </div>

    </div>


    <?php include 'includes/footer.php'; ?>


    <!-- SLIDER JAVASCRIPT -->
    <script>

        function scrollMovies(direction) {

            const movies = document.getElementById("moviesScroll");

            movies.scrollBy({
                left: direction * 500,
                behavior: "smooth"
            });

        }

    </script>

</body>

</html>