-- accounts voor het admin panel
CREATE TABLE IF NOT EXISTS admin_log (
    admin_id       INT AUTO_INCREMENT PRIMARY KEY,
    gebruikersnaam VARCHAR(50) NOT NULL,
    wachtwoord     VARCHAR(255) NOT NULL
);

-- admin toevoegen, hash maken met: php -r "echo password_hash('jouw wachtwoord', PASSWORD_DEFAULT);"
-- INSERT INTO admin_log (gebruikersnaam, wachtwoord) VALUES ('admin', 'hier de hash');
