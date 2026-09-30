-- 1 keer uitvoeren op de oude database (van voor de api)
ALTER TABLE reservering DROP FOREIGN KEY reservering_ibfk_1;

-- oude testdata weghalen
DELETE FROM reservering;

DROP TABLE IF EXISTS voorstelling;
DROP TABLE IF EXISTS movies;
