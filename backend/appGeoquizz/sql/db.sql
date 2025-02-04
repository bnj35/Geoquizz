CREATE DATABASE geoquizz_partie;

USE geoquizz_partie;

CREATE TABLE IF NOT EXISTS parties (
    id CHAR(36) NOT NULL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    nb_photos INT NOT NULL,
    score INT NOT NULL,
    theme VARCHAR(255) NOT NULL
);

INSERT INTO parties (id, nom, token, nb_photos, score, theme) VALUES 
('2', 'Partie 2', 'token2', 10, 0, 'Paris'),
('3', 'Partie 3', 'token3', 8, 0, 'London'),
('4', 'Partie 4', 'token4', 6, 0, 'New York'),
('5', 'Partie 5', 'token5', 7, 0, 'Tokyo'),
('6', 'Partie 6', 'token6', 9, 0, 'Berlin'),
('7', 'Partie 7', 'token7', 5, 0, 'Sydney'),
('8', 'Partie 8', 'token8', 11, 0, 'Rome'),
('9', 'Partie 9', 'token9', 4, 0, 'Madrid'),
('10', 'Partie 10', 'token10', 12, 0, 'Toronto'),
('11', 'Partie 11', 'token11', 3, 0, 'Dubai');
