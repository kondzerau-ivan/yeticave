CREATE DATABASE IF NOT EXISTS yeticave DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
USE yeticave;
CREATE TABLE IF NOT EXISTS categories
(
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(128) NOT NULL UNIQUE,
  symbol VARCHAR(128) NOT NULL UNIQUE
);
CREATE TABLE IF NOT EXISTS users
(
  id INT AUTO_INCREMENT PRIMARY KEY,
  registered_at DATETIME DEFAULT NOW(),
  email VARCHAR(320) UNIQUE NOT NULL,
  login VARCHAR(128) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  contacts TEXT NOT NULL,
  lot_id INT NOT NULL,
  bet_id INT NOT NULL,
  FOREIGN KEY (lot_id) REFERENCES lots(id),
  FOREIGN KEY (bet_id) REFERENCES bets(id)
);
CREATE TABLE IF NOT EXISTS lots
(
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at DATETIME DEFAULT NOW(),
  title VARCHAR(128) NOT NULL,
  description TEXT NOT NULL,
  image_url VARCHAR(128) NOT NULL,
  start_price INT NOT NULL,
  finished_at DATETIME NOT NULL,
  bet_range INT NOT NULL,
  author_id INT NOT NULL,
  winner_id INT NOT NULL,
  category_id INT NOT NULL,
  FOREIGN KEY (author_id) REFERENCES users(id),
  FOREIGN KEY (winner_id) REFERENCES users(id),
  FOREIGN KEY (category_id) REFERENCES categories(id)
);
CREATE TABLE IF NOT EXISTS bets
(
  id INT AUTO_INCREMENT PRIMARY KEY,
  placed_at DATETIME DEFAULT NOW(),
  amount INT NOT NULL,
  user_id INT NOT NULL,
  lot_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (lot_id) REFERENCES lots(id)
);