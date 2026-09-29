-- Eenmalig uitvoeren op een database waar de tabel reservering al bestaat.
ALTER TABLE reservering
    ADD COLUMN naam  VARCHAR(100) NOT NULL DEFAULT '' AFTER stoel,
    ADD COLUMN email VARCHAR(255) NOT NULL DEFAULT '' AFTER naam;
