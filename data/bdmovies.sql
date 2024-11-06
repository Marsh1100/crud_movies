
CREATE DATABASE dbmovies;
USE dbmovies;
CREATE TABLE countries(
id INT(10) AUTO_INCREMENT PRIMARY KEY NOT NULL,
name VARCHAR(50) NOT NULL UNIQUE
);
CREATE TABLE genres(
id INT(10) AUTO_INCREMENT PRIMARY KEY NOT NULL,
name VARCHAR(50) NOT NULL UNIQUE
);
CREATE TABLE staff(
id INT(10) AUTO_INCREMENT PRIMARY KEY NOT NULL,
name VARCHAR(50) NOT NULL UNIQUE
);
CREATE TABLE movies (
id INT(10) AUTO_INCREMENT PRIMARY KEY NOT NULL,
title VARCHAR(50) NOT NULL UNIQUE,
year YEAR NOT NULL,
id_country INT(10) NOT NULL,
url VARCHAR(2048),
CONSTRAINT FK_id_country FOREIGN KEY (id_country)
	REFERENCES countries(id)
);
CREATE TABLE movies_genres (
id_movie INT(10) NOT NULL,
id_genre INT(10) NOT NULL,
CONSTRAINT FK_movie FOREIGN KEY (id_movie) REFERENCES movies(id) ON DELETE CASCADE,
CONSTRAINT FK_genre FOREIGN KEY (id_genre) REFERENCES genres(id),
PRIMARY KEY (id_movie, id_genre)
);

CREATE TABLE movies_staff (
    id_movie INT(10) NOT NULL,
    id_person INT(10) NOT NULL,
    protagonist TINYINT(3) NOT NULL,
    director TINYINT(3) NOT NULL,
    CONSTRAINT FK_movie_staff FOREIGN KEY (id_movie) REFERENCES movies(id) ON DELETE CASCADE,
    CONSTRAINT FK_person FOREIGN KEY (id_person) REFERENCES staff(id),
    PRIMARY KEY (id_movie, id_person)  
);

INSERT INTO countries (name) VALUES
    ('Estados Unidos'), ('India'), ('China'), ('Japón'), ('Reino Unido'),('Francia'),
    ('Alemania'), ('Italia'), ('Corea del Sur'), ('Brasil'), ('Argentina'), ('México'), ('España'), 
    ('Canadá'), ('Australia'), ('Colombia'), ('Países Bajos'), ('Suiza'), ('Rusia'),
    ('Turquía');

INSERT INTO genres (name) VALUES
    ('Acción'), ('Comedia'), ('Fantasía'), ('Terror'), ('Drama'), ('Aventura'),
    ('Ciencia Ficción'), ('Suspenso'), ('Romance'), ('Musical'), ('Animación'),
    ('Documental'), ('Misterio'), ('Histórico'), ('Thriller');

INSERT INTO movies (title, year, id_country, url) VALUES
    ('Inception', 2010, 1, 'https://m.media-amazon.com/images/S/pv-target-images/cc72ff2193c0f7a85322aee988d6fe1ae2cd9f8800b6ff6e8462790fe2aacaf3.jpg'),
    ('Parasite', 2019, 9, 'https://m.media-amazon.com/images/S/pv-target-images/67f4f4fc5fc273fb0cb8a2417be73b9d650c3ead7bcf5b710bedb520d8b006c2.jpg'),
    ('Romper el Círculo', 2024, 14, 'https://images.cdn2.buscalibre.com/fit-in/360x360/c1/e1/c1e1b8d51a700ef6a251562cb9ff7cef.jpg'),
    ('The Godfather', 1972, 1, 'https://play-lh.googleusercontent.com/ZucjGxDqQ-cHIN-8YA1HgZx7dFhXkfnz73SrdRPmOOHEax08sngqZMR_jMKq0sZuv5P7-T2Z2aHJ1uGQiys'),
    ('Amélie', 2001, 6, 'https://pics.filmaffinity.com/Amaelie-848337470-mmed.jpg');

INSERT INTO staff (name) VALUES
    ('Christopher Nolan'),  -- Inception, Director
    ('Leonardo DiCaprio'),  -- Inception, Protagonista
    ('Joseph Gordon-Levitt'),  -- Inception, Actor secundario
    ('Bong Joon-ho'),  -- Parasite, Director
    ('Song Kang-ho'),  -- Parasite, Protagonista
    ('Choi Woo-shik'),  -- Parasite, Actor secundario
    ('Justin Baldoni'),  -- Romper el cícurlo, Director
    ('Jenny Slate'),  -- Romper el cícurlo, Protagonista
    ('Blake Lively'),  -- Romper el cícurlo, Protagonista
    ('Francis Ford Coppola'),  -- The Godfather, Director
    ('Marlon Brando'),  -- The Godfather, Protagonista
    ('Al Pacino'),  -- The Godfather, Actor secundario
    ('Jean-Pierre Jeunet'),  -- Amélie, Director
    ('Audrey Tautou'),  -- Amélie, Protagonista
    ('Mathieu Kassovitz');  -- Amélie, Actor secundario

INSERT INTO movies_staff (id_movie, id_person, protagonist, director) VALUES
    (1, 1, false, true),  -- Inception, Christopher Nolan (Director)
    (1, 2, true, false),  -- Inception, Leonardo DiCaprio (Protagonista)
    (1, 3, false, false),  -- Inception, Joseph Gordon-Levitt (Actor secundario)

    (2, 4, false, true),  -- Parasite, Bong Joon-ho (Director)
    (2, 5, true, false),  -- Parasite, Song Kang-ho (Protagonista)
    (2, 6, false, false),  -- Parasite, Choi Woo-shik (Actor secundario)

    (3, 7, true, true),  -- Romper el círculo, Justin Baldoni (Director-Protagonista)
    (3, 8, false, false),  -- Romper el cículo, Jenny Slate (Actor secundario)
    (3, 9, true, false),  -- Romper el círculo, Blake Lively (Protagonista)

    (4, 10, false, true),  -- The Godfather, Francis Ford Coppola (Director)
    (4, 11, true, false),  -- The Godfather, Marlon Brando (Protagonista)
    (4, 12, false, false),  -- The Godfather, Al Pacino (Actor secundario)

    (5, 13, false, true),  -- Amélie, Jean-Pierre Jeunet (Director)
    (5, 14, true, false),  -- Amélie, Audrey Tautou (Protagonista)
    (5, 15, false, false);  -- Amélie, Mathieu Kassovitz (Actor secundario)
    
INSERT INTO movies_genres (id_movie, id_genre) VALUES
    -- Inception (Aventura, Ciencia Ficción, Suspenso)
    (1, 6),  -- Inception, Aventura
    (1, 7),  -- Inception, Ciencia Ficción
    (1, 8),  -- Inception, Suspenso

    -- Parasite (Drama, Misterio)
    (2, 5),  -- Parasite, Drama
    (2, 13), -- Parasite, Misterio

    -- Romper el círculo  (Romance,, Drama)
    (3, 9),  -- Romper el círculo, Romance
    (3, 5),  -- Romper el círculo, Drama

    -- The Godfather (Crimen, Drama)
    (4, 5),  -- The Godfather, Drama
    (4, 8),  -- The Godfather, Suspenso

    -- Amélie (Romance, Comedia)
    (5, 9),  -- Amélie, Romance
    (5, 2);  -- Amélie, Comedia