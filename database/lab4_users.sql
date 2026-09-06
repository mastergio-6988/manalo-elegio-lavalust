-- Laboratory Activity No. 4 database setup for LavaLust.
-- In Navicat/Aiven, run this script after connecting to your MySQL service.

CREATE DATABASE IF NOT EXISTS mydb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mydb;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    username VARCHAR(100) NOT NULL
);

INSERT INTO users (id, firstname, lastname, email, username) VALUES
    (1, 'Juan', 'Dela Cruz', 'juan@example.com', 'juandelacruz'),
    (2, 'Maria', 'Santos', 'maria@example.com', 'mariasantos'),
    (3, 'Pedro', 'Garcia', 'pedro@example.com', 'pedrogarcia'),
    (4, 'Ana', 'Reyes', 'ana@example.com', 'anareyes'),
    (5, 'Jose', 'Mendoza', 'jose@example.com', 'josemendoza')
ON DUPLICATE KEY UPDATE
    firstname = VALUES(firstname),
    lastname = VALUES(lastname),
    email = VALUES(email),
    username = VALUES(username);

SELECT id, firstname, lastname, email, username FROM users ORDER BY id;
