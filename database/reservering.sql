-- Gereserveerde stoelen. voorstelling_id is de showtimeId uit de API, daarom geen foreign key.
-- UNIQUE zorgt dat een stoel per voorstelling maar één keer geboekt kan worden.
-- naam en email: van wie de reservering is, zodat de medewerker het kan zien.
CREATE TABLE IF NOT EXISTS reservering (
    reservering_id  INT AUTO_INCREMENT PRIMARY KEY,
    voorstelling_id INT NOT NULL,
    stoel           VARCHAR(3) NOT NULL,
    naam            VARCHAR(100) NOT NULL DEFAULT '',
    email           VARCHAR(255) NOT NULL DEFAULT '',
    UNIQUE (voorstelling_id, stoel)
);
