USE yeticave;
INSERT INTO categories(title, symbol)
VALUES('Доски и лыжи', 'boards'),
      ('Крепления', 'attachment'),
      ('Ботинки', 'boots'),
      ('Одежда', 'clothing'),
      ('Инструменты', 'tools'),
      ('Разное', 'other');

INSERT INTO users(email, login, password, contacts)
VALUES('ivanov@mail.com', 'ivanov', MD5('ivanov123'), '+552522142'),
      ('bilbo@mail.com', 'bilbo', MD5('bilbo123'), 'Shire'),
      ('tony@mail.com', 'stark', MD5('iloveyou3000'), '+6465222585');

INSERT INTO lots(title, image_url, start_price, finished_at, author_id, category_id)
VALUES('2014 Rossignol District Snowboard', 'img/lot-1.jpg', 10999, '2022-10-15', 2, 1),
      ('DC Ply Mens 2016/2017 Snowboard', 'img/lot-2.jpg', 159999, '2022-10-15', 3, 1),
      ('Крепления Union Contact Pro 2015 года размер L/XL', 'img/lot-3.jpg', 8000, '2022-10-12', 2, 2),
      ('Ботинки для сноуборда DC Mutiny Charocal', 'img/lot-4.jpg', 10999, '2022-10-12 04:20', 3, 3),
      ('Куртка для сноуборда DC Mutiny Charocal', 'img/lot-5.jpg', 7500, '2022-10-13', 3, 4),
      ('Маска Oakley Canopy', 'img/lot-6.jpg', 5400, '2022-10-14', 2, 6);

INSERT INTO bets(amount, user_id, lot_id)
VALUES('9000', 1, 3),
      ('6400', 2, 6);

SELECT title FROM categories;

SELECT lots.title, lots.start_price, lots.image_url, categories.title
FROM lots INNER JOIN categories ON lots.category_id = categories.id
ORDER BY lots.created_at DESC
LIMIT 3;

SELECT lots.title, categories.title
FROM lots INNER JOIN categories ON lots.category_id = categories.id
WHERE lots.id = 1;

UPDATE lots SET title = '2014 Rossignol District Snowboard'
WHERE lots.id = 1;

SELECT * FROM bets
WHERE lot_id = 3
ORDER BY placed_at DESC;