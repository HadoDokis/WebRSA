/* TODO: Enlever php_sid de jetons */

/* Patch: on peut insérer plusieurs fois le même utilisateur -> à faire deans le modèle */
ALTER TABLE users ADD CONSTRAINT users_username_key UNIQUE (username);

CREATE TABLE connections (
    id          SERIAL NOT NULL PRIMARY KEY,
    user_id     INT NOT NULL references users(id),
    php_sid     CHAR(32) DEFAULT NULL,
    created     TIMESTAMP DEFAULT NULL,
    modified    TIMESTAMP DEFAULT NULL
);