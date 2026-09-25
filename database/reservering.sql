-- Забронированные кресла. UNIQUE не даёт забронировать одно кресло дважды на один сеанс.
CREATE TABLE IF NOT EXISTS reservering (
    reservering_id  INT AUTO_INCREMENT PRIMARY KEY,
    voorstelling_id INT NOT NULL,
    stoel           VARCHAR(3) NOT NULL,
    UNIQUE (voorstelling_id, stoel),
    FOREIGN KEY (voorstelling_id) REFERENCES voorstelling(voorstelling_id)
);
