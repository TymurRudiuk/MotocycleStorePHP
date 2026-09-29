INSERT INTO admins (login, password_hash)
VALUES (
    'admin',
    '$2y$10$2GaM8TzM0oO8L5Rdz4M0M.k4H3LrY6qJ2Y0G4G3oM7mT0Q2M0LJ2a'
)
ON DUPLICATE KEY UPDATE login = VALUES(login);
