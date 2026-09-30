-- 1 keer uitvoeren als je de tabel reservering al hebt
ALTER TABLE reservering
    ADD COLUMN naam  VARCHAR(100) NOT NULL DEFAULT '' AFTER stoel,
    ADD COLUMN email VARCHAR(255) NOT NULL DEFAULT '' AFTER naam;
