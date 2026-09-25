<?php
require_once 'db.php';

$stmt = $pdo->query("SELECT * FROM movies ORDER BY movie_id");
$films = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">

<?php include 'includes/head.php'; ?>

<body>
<?php include 'includes/header.php'; ?>

<main class="mx-auto max-w-7xl px-5 py-12 text-white sm:px-8 lg:px-10">
    <div class="mb-10 flex items-end justify-between gap-4 border-b border-white/20 pb-5">
        <div>
            <p class="mb-2 text-sm font-semibold uppercase tracking-[0.25em] text-[#c58aaa]">AnnexBios Bilthoven</p>
            <h1 class="text-4xl font-bold md:text-5xl">Filmagenda</h1>
        </div>
        <span class="hidden text-right text-sm text-gray-300 sm:block">
            <?= count($films) ?> films in de agenda
        </span>
    </div>

    <?php if ($films): ?>
        <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <?php foreach ($films as $film): ?>
                <article class="group flex h-full flex-col overflow-hidden rounded-lg border border-white/10 bg-[rgb(20,23,25)] shadow-xl transition duration-300 hover:-translate-y-1 hover:border-[#67294c] hover:shadow-2xl">
                    <div class="relative overflow-hidden bg-black">
                        <img
                            src="<?= htmlspecialchars($film['poster'] ?? 'style/images/Placeholder.png') ?>"
                            alt="Poster van <?= htmlspecialchars($film['title']) ?>"
                            class="aspect-[2/3] w-full object-cover transition duration-500 group-hover:scale-105"
                        >
                        <span class="absolute right-3 top-3 rounded bg-black/80 px-3 py-1 text-sm font-semibold text-white">
                            <?= htmlspecialchars($film['imd_rating'] ?? '-') ?> / 10
                        </span>
                    </div>

                    <div class="flex flex-1 flex-col p-5">
                        <h2 class="mb-3 text-xl font-bold text-white">
                            <?= htmlspecialchars($film['title']) ?>
                        </h2>

                        <p class="mb-5 line-clamp-3 text-sm leading-relaxed text-gray-300">
                            <?= htmlspecialchars($film['description'] ?? 'Geen beschrijving beschikbaar.') ?>
                        </p>

                        <div class="mt-auto border-t border-white/10 pt-4">
                            <p class="mb-4 text-sm text-gray-400">
                                Release: <?= htmlspecialchars($film['release_date'] ?? '-') ?>
                            </p>
                            <a
                                href="film-detail.php?id=<?= (int) $film['movie_id'] ?>"
                                class="block rounded-md bg-[#67294c] px-4 py-3 text-center font-semibold text-white transition-colors hover:bg-[#813460]"
                            >
                                Bekijk film
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="rounded-lg border border-white/10 bg-[rgb(20,23,25)] p-8 text-center text-gray-300">
            Er zijn momenteel geen films beschikbaar.
        </p>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>