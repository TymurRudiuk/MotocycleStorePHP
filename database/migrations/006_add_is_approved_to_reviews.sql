ALTER TABLE reviews ADD COLUMN is_approved TINYINT(1) NOT NULL DEFAULT 0;
UPDATE reviews SET is_approved = 1;
