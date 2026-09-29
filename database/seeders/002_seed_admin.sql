INSERT INTO admins (login, password_hash)
VALUES (
    'admin',
    '$2y$10$AWrcK942t/PtWvKTJN2kuOqb2akt/cyxuUK2XY2HZn6W5lP391eE6'
)
ON DUPLICATE KEY UPDATE login = VALUES(login);
