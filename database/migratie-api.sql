-- Eenmalig uitvoeren op een database van vóór de overstap naar de API.
ALTER TABLE reservering DROP FOREIGN KEY reservering_ibfk_1;

-- Oude testreserveringen horen bij de oude voorstellingen
DELETE FROM reservering;

DROP TABLE IF EXISTS voorstelling;
DROP TABLE IF EXISTS movies;
