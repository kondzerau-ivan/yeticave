CREATE DATABASE IF NOT EXISTS yeticave DEFAULT CHARACTER SET utf8mb4 DEFAULT COLLATE utf8mb4_unicode_ci;
USE yeticave;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registration_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    email VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    contacts VARCHAR(255) NOT NULL
);
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(127) NOT NULL,
    code VARCHAR(127) NOT NULL UNIQUE
);
CREATE TABLE lots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) NOT NULL,
    price INT NOT NULL,
    expiration_date DATETIME NOT NULL,
    step INT NOT NULL,
    author_id INT NOT NULL,
    winner_id INT DEFAULT NULL,
    category_id INT NOT NULL,
    INDEX idx_expiration_date (expiration_date),
    FOREIGN KEY (author_id) REFERENCES users(id),
    FOREIGN KEY (winner_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);
CREATE TABLE bets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    amount INT NOT NULL,
    user_id INT NOT NULL,
    lot_id INT NOT NULL,
    INDEX idx_created_at (created_at),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (lot_id) REFERENCES lots(id)
);
CREATE FULLTEXT INDEX lot_ft_search ON lots(name, description);
