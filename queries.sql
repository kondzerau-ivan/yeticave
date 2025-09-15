INSERT INTO categories (name, code)
VALUES ('Доски и лыжи', 'boards'),
    ('Крепления', 'attachment'),
    ('Ботинки', 'boots'),
    ('Одежда', 'clothing'),
    ('Инструменты', 'tools'),
    ('Разное', 'other');
INSERT INTO users (email, name, password, contacts)
VALUES (
        'stark@gmail.com',
        'tony',
        'bestofthebest',
        '+000777000'
    ),
    (
        'batman@gmail.com',
        'batman',
        '666666',
        '+000666000'
    );
INSERT INTO lots (
        name,
        description,
        image,
        price,
        expiration_date,
        step,
        author_id,
        category_id
    )
VALUES (
        '2014 Rossignol District Snowboard',
        'Офигенный Snowboard',
        'img/lot-1.jpg',
        10999,
        '2025-10-01',
        100,
        1,
        1
    ),
    (
        'DC Ply Mens 2016/2017 Snowboard',
        'Супер Snowboard',
        'img/lot-2.jpg',
        15999,
        '2025-9-11 17:10',
        500,
        1,
        1
    ),
    (
        'Крепления Union Contact Pro 2015 года размер L/XL',
        'Супер Крепления',
        'img/lot-3.jpg',
        8000,
        '2025-11-01',
        500,
        1,
        2
    ),
    (
        'Ботинки для сноуборда DC Mutiny Charocal',
        'Супер Ботинки для сноуборда',
        'img/lot-4.jpg',
        10999,
        '2025-11-15',
        500,
        1,
        3
    ),
    (
        'Куртка для сноуборда DC Mutiny Charocal',
        'Супер Куртка для сноуборда',
        'img/lot-5.jpg',
        7500,
        '2025-12-01',
        500,
        1,
        4
    ),
    (
        'Маска Oakley Canopy',
        'Супер Маска',
        'img/lot-6.jpg',
        5400,
        '2025-12-15',
        500,
        2,
        6
    );
INSERT INTO bets (amount, user_id, lot_id)
VALUES (5900, 1, 6),
    (11099, 2, 1);
