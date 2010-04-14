-- --------------------------------------------------------------------------------------------------------
--  ....Table des thèmes pour les types d'aides de l'APRE
-- --------------------------------------------------------------------------------------------------------
CREATE TABLE themesapres66 (
    id                      SERIAL NOT NULL PRIMARY KEY,
    name                     VARCHAR(200)
);
CREATE INDEX themesapres66_name_idx ON themesapres66 (name);
COMMENT ON TABLE themesapres66 IS 'Liste des types d''aides pour l''APRE CG66';

-- --------------------------------------------------------------------------------------------------------
--  ....Table des aides liées à l'APRE
-- --------------------------------------------------------------------------------------------------------
CREATE TABLE aidesapres66 (
    id                      SERIAL NOT NULL PRIMARY KEY,
    themeapre66_id          INTEGER NOT NULL REFERENCES themesapres66(id),
    name                    VARCHAR(200),
    plafond                 DECIMAL(10,2)
);
CREATE INDEX aidesapres66_themeapre66_id_idx ON aidesapres66 (themeapre66_id);
COMMENT ON TABLE aidesapres66 IS 'Table pour les aides liées à l''APRE CG66';

-- --------------------------------------------------------------------------------------------------------
--  ....Table des pièces pour les types d'aides de l'APRE
-- --------------------------------------------------------------------------------------------------------
CREATE TABLE piecesaides66 (
    id                          SERIAL NOT NULL PRIMARY KEY,
    name                        VARCHAR(200)
);
COMMENT ON TABLE piecesaides66 IS 'Table pour les pièces liées aux aides de l''APRE CG66';

-- --------------------------------------------------------------------------------------------------------
--  ....Table des pièces liées aux aides de l'APRE
-- --------------------------------------------------------------------------------------------------------
CREATE TABLE aidesapres66_piecesaides66 (
    id                          SERIAL NOT NULL PRIMARY KEY,
    aideapre66_id               INTEGER NOT NULL REFERENCES aidesapres66(id),
    pieceaide66_id              INTEGER NOT NULL REFERENCES piecesaides66(id)
);
CREATE INDEX aidesapres66_piecesaides66_aideapre66_id_idx ON aidesapres66_piecesaides66 (aideapre66_id);
CREATE INDEX aidesapres66_piecesaides66_pieceaide66_id_idx ON aidesapres66_piecesaides66 (pieceaide66_id);
COMMENT ON TABLE aidesapres66_piecesaides66 IS 'Table pour les pièces liées aux aides de l''APRE CG66';
