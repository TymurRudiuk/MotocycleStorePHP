INSERT INTO motorcycle_classes (name)
VALUES
    ('Sport'),
    ('Naked'),
    ('Sport Touring'),
    ('Roadster'),
    ('Adventure'),
    ('Enduro'),
    ('Cruiser'),
    ('Touring'),
    ('Dual Sport'),
    ('Scooter'),
    ('Cafe Racer'),
    ('Supermoto')
ON DUPLICATE KEY UPDATE name = VALUES(name);
