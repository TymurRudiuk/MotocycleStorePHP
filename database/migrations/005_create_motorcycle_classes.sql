CREATE TABLE IF NOT EXISTS motorcycle_classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE motorcycles
    ADD COLUMN class_id INT NULL AFTER brand;

INSERT INTO motorcycle_classes (name)
SELECT DISTINCT type
FROM motorcycles
WHERE type IS NOT NULL
  AND type <> ''
  AND type NOT IN (SELECT name FROM motorcycle_classes);

UPDATE motorcycles m
JOIN motorcycle_classes mc ON mc.name = m.type
SET m.class_id = mc.id
WHERE m.class_id IS NULL;

ALTER TABLE motorcycles
    ADD CONSTRAINT fk_motorcycles_class_id
    FOREIGN KEY (class_id) REFERENCES motorcycle_classes(id)
    ON DELETE SET NULL;
