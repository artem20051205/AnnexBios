<footer class="flex flex-col items-center justify-between gap-4 rounded-[10px] bg-[rgb(20,23,25)] px-6 py-5 text-white sm:flex-row sm:px-10">

    <p class="m-0 text-sm text-gray-300">
        © 2026 AnnexBios Bilthoven
    </p>

    <!-- $base komt uit header.php, zodat de links ook vanuit admin werken -->
    <div class="flex flex-wrap justify-center gap-10">
        <a
            href="<?= $base ?? '' ?>index.php"
            class="text-sm text-white transition-colors duration-300 hover:text-[#67294c]"
        >
            Home
        </a>

        <a
            href="<?= $base ?? '' ?>films.php"
            class="text-sm text-white transition-colors duration-300 hover:text-[#67294c]"
        >
            Filmagenda
        </a>

        <a
            href="<?= $base ?? '' ?>bestellen.php"
            class="text-sm text-white transition-colors duration-300 hover:text-[#67294c]"
        >
            Bestellen
        </a>
    </div>

</footer>
