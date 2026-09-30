-- voorstelling_id is de showtimeId uit api.php, daarom geen foreign key
-- UNIQUE: een stoel kan maar 1 keer per voorstelling geboekt worden
CREATE TABLE IF NOT EXISTS reservering (
    reservering_id  INT AUTO_INCREMENT PRIMARY KEY,
    voorstelling_id INT NOT NULL,
    stoel           VARCHAR(3) NOT NULL, -- bv. A1
    naam            VARCHAR(100) NOT NULL DEFAULT '',
    email           VARCHAR(255) NOT NULL DEFAULT '',
    UNIQUE (voorstelling_id, stoel)
);
