<?php
// Pagina's in de map admin/ zetten $base = '../', zodat de links ook daar kloppen
$base = $base ?? '';
?>
<header class="flex min-h-[120px] flex-col items-start gap-4 rounded-[10px] bg-[rgb(20,23,25)] px-6 py-5 sm:flex-row sm:items-center sm:px-10">

    <a href="<?= $base ?>index.php">
        <img
            src="<?= $base ?>style/images/logo.png"
            alt="Logo"
            class="w-[240px] max-w-full sm:w-[300px]"
        >
    </a>

    <nav class="ml-0 flex flex-wrap items-center gap-x-6 gap-y-2 sm:ml-[100px] sm:gap-x-[45px]">
        <a href="<?= $base ?>index.php" class="relative py-2 text-xl text-white transition-colors duration-300 after:absolute after:bottom-0 after:left-0 after:h-[3px] after:w-0 after:bg-white after:transition-all after:duration-300 hover:text-[#67294c] hover:after:w-full">
            Home
        </a>

        <a href="<?= $base ?>films.php" class="relative py-2 text-xl text-white transition-colors duration-300 after:absolute after:bottom-0 after:left-0 after:h-[3px] after:w-0 after:bg-white after:transition-all after:duration-300 hover:text-[#67294c] hover:after:w-full">
            Filmagenda
        </a>

        <a href="<?= $base ?>contact.php" class="relative py-2 text-xl text-white transition-colors duration-300 after:absolute after:bottom-0 after:left-0 after:h-[3px] after:w-0 after:bg-white after:transition-all after:duration-300 hover:text-[#67294c] hover:after:w-full">
            Contact
        </a>



        <a href="<?= $base ?>admin/index.php" class="relative py-2 text-xl text-white transition-colors duration-300 after:absolute after:bottom-0 after:left-0 after:h-[3px] after:w-0 after:bg-white after:transition-all after:duration-300 hover:text-[#67294c] hover:after:w-full">
            Admin
        </a>
    </nav>

</header>
