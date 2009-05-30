ALTER TABLE dspps DROP COLUMN accoemploi;
-- -----------------------------------------------------------------------------
--       table : accoemplois
-- -----------------------------------------------------------------------------
CREATE TABLE accoemplois (
    id      SERIAL NOT NULL PRIMARY KEY,
    code    CHAR(4),
    name    VARCHAR(100)
);


CREATE TABLE dspps_accoemplois (
    accoemploi_id   INTEGER NOT NULL REFERENCES accoemplois(id),
    dspp_id     INTEGER NOT NULL REFERENCES dspps(id)
);

-- -----------------------------------------------------------------------
-- Modification de la table creancesalimentaires (ajout de codes xml)
-- -----------------------------------------------------------------------
ALTER TABLE creancesalimentaires ADD COLUMN topdemdisproccrealim BOOLEAN;
ALTER TABLE creancesalimentaires ADD COLUMN engproccrealim CHAR(1);
ALTER TABLE creancesalimentaires ADD COLUMN verspa CHAR(1);
ALTER TABLE creancesalimentaires ADD COLUMN topjugpa BOOLEAN;

ALTER TABLE orientsstructs ALTER COLUMN structurereferente_id DROP NOT NULL;