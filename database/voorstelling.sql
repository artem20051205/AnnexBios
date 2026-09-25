-- Сеансы (voorstelling) по ERD. zaal_id пока без FK: таблицы zaal ещё нет.
CREATE TABLE IF NOT EXISTS voorstelling (
    voorstelling_id INT AUTO_INCREMENT PRIMARY KEY,
    film_id         INT NOT NULL,
    zaal_id         INT NULL,
    datum           DATE NOT NULL,
    begintijd       TIME NOT NULL,
    prijs           DECIMAL(5,2) NOT NULL,
    FOREIGN KEY (film_id) REFERENCES movies(movie_id)
);

-- Testdata: 3 сеанса завтра для каждого активного фильма без сеансов
INSERT INTO voorstelling (film_id, zaal_id, datum, begintijd, prijs)
SELECT m.movie_id, 1, CURDATE() + INTERVAL 1 DAY, t.begintijd, 11.50
FROM movies m
CROSS JOIN (SELECT '14:00:00' AS begintijd UNION ALL SELECT '17:30:00' UNION ALL SELECT '20:45:00') t
WHERE m.active = 1
  AND NOT EXISTS (SELECT 1 FROM voorstelling v WHERE v.film_id = m.movie_id);
