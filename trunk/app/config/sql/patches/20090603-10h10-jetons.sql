CREATE TABLE jetons (
    id          SERIAL NOT NULL PRIMARY KEY,
    dossier_id  INT NOT NULL references dossiers_rsa(id),
    php_sid     CHAR(32) DEFAULT NULL,
    user_id     INT NOT NULL references users(id),
    created     TIMESTAMP DEFAULT NULL,
    modified    TIMESTAMP DEFAULT NULL
);