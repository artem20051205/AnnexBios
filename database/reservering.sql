-- Gereserveerde stoelen. voorstelling_id is de showtimeId uit de API, daarom geen foreign key.
-- UNIQUE zorgt dat een stoel per voorstelling maar één keer geboekt kan worden.
CREATE TABLE IF NOT EXISTS reservering (
    reservering_id  INT AUTO_INCREMENT PRIMARY KEY,
    voorstelling_id INT NOT NULL,
    stoel           VARCHAR(3) NOT NULL,
    UNIQUE (voorstelling_id, stoel)
);
