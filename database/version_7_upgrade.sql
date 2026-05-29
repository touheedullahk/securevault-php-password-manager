USE securevault;

ALTER TABLE users
    ADD COLUMN encrypted_vault_key TEXT NULL AFTER password_hash;

CREATE TABLE IF NOT EXISTS password_records (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    service_name VARCHAR(60) NOT NULL,
    encrypted_password TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_record_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
);
