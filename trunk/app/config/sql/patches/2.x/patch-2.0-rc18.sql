SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

-- *****************************************************************************
BEGIN;
-- *****************************************************************************

DROP TABLE IF EXISTS courrierspdos CASCADE;
DROP TABLE IF EXISTS courrierspdos_traitementspdos CASCADE;
DROP TABLE IF EXISTS textareascourrierspdos CASCADE;
DROP TABLE IF EXISTS contenustextareascourrierspdos CASCADE;


DROP INDEX IF EXISTS courrierspdos_name_idx ;
DROP INDEX IF EXISTS courrierspdos_modeleodt_idx;

DROP INDEX IF EXISTS courrierspdos_traitementspdos_traitementpdo_id_idx;
DROP INDEX IF EXISTS courrierspdos_traitementspdos_courrierpdo_id_idx;

DROP INDEX IF EXISTS textareascourrierspdos_courrierpdo_id_idx;
DROP INDEX IF EXISTS textareascourrierspdos_ordre_idx;

DROP INDEX IF EXISTS contenustextareascourrierspdos_courrierpdo_id_idx;
DROP INDEX IF EXISTS contenustextareascourrierspdos_courrierpdo_traitementpdo_id_idx;


CREATE TABLE courrierspdos (
    id                      SERIAL NOT NULL PRIMARY KEY,
    name                    VARCHAR(255) NOT NULL,
    modeleodt               VARCHAR(255) NOT NULL
);
COMMENT ON TABLE courrierspdos IS 'Liste des courriers liés à un traitement de PDO (CG66)';
CREATE INDEX courrierspdos_name_idx ON courrierspdos( name );
CREATE INDEX courrierspdos_modeleodt_idx ON courrierspdos( modeleodt );


--************************************************************************************************
CREATE TABLE courrierspdos_traitementspdos (
    id              SERIAL NOT NULL PRIMARY KEY,
    courrierpdo_id       INTEGER NOT NULL REFERENCES courrierspdos(id) ON DELETE CASCADE ON UPDATE CASCADE,
    traitementpdo_id          INTEGER NOT NULL REFERENCES traitementspdos(id) ON DELETE CASCADE ON UPDATE CASCADE

);
COMMENT ON TABLE courrierspdos_traitementspdos IS 'Table de liaison entre les courriers et le traitement d''une PDO (CG66)';
CREATE INDEX courrierspdos_traitementspdos_courrierpdo_id_idx ON courrierspdos_traitementspdos (courrierpdo_id);
CREATE INDEX courrierspdos_traitementspdos_traitementpdo_id_idx ON courrierspdos_traitementspdos (traitementpdo_id);

-- *****************************************************************************

CREATE TABLE textareascourrierspdos (
    id                      SERIAL NOT NULL PRIMARY KEY,
    courrierpdo_id       INTEGER NOT NULL REFERENCES courrierspdos(id) ON DELETE CASCADE ON UPDATE CASCADE,
    nomchampodt             VARCHAR(250) NOT NULL,
    name                 TEXT NOT NULL,
    ordre           INTEGER NOT NULL
);
COMMENT ON TABLE textareascourrierspdos IS 'Table permettant de lier les zones de commentaires à un courrier de PDO (CG66)';
CREATE INDEX textareascourrierspdos_courrierpdo_id_idx ON textareascourrierspdos( courrierpdo_id );
CREATE INDEX textareascourrierspdos_ordre_idx ON textareascourrierspdos( ordre );


-- ************************************
CREATE TABLE contenustextareascourrierspdos (
    id                                      SERIAL NOT NULL PRIMARY KEY,
    textareacourrierpdo_id                  INTEGER NOT NULL REFERENCES textareascourrierspdos(id) ON DELETE CASCADE ON UPDATE CASCADE,
    courrierpdo_traitementpdo_id            INTEGER NOT NULL REFERENCES courrierspdos_traitementspdos(id) ON DELETE CASCADE ON UPDATE CASCADE,
    contenu                                 TEXT NOT NULL
);
COMMENT ON TABLE contenustextareascourrierspdos IS 'Table de liaison entre les courriers PDOs et le nombre de textarea à ajouter (CG66)';
CREATE INDEX contenustextareascourrierspdos_courrierpdo_id_idx ON contenustextareascourrierspdos( textareacourrierpdo_id );
CREATE INDEX contenustextareascourrierspdos_courrierpdo_traitementpdo_id_idx ON contenustextareascourrierspdos( courrierpdo_traitementpdo_id );
-- ************************************

-- 20110411
DROP INDEX IF EXISTS traitementspdos_dtfinperiode_idx;
SELECT public.alter_columnname_ifexists( 'public', 'traitementspdos', 'dtfinperiode', 'datefinperiode' );
SELECT public.alter_columnname_ifexists( 'public', 'traitementspdos', 'dureeecheance', 'dureefinperiode' );
DROP INDEX IF EXISTS traitementspdos_datefinperiode_idx;
CREATE INDEX traitementspdos_datefinperiode_idx ON traitementspdos (datefinperiode);

-- -----------------------------------------------------------------------------
-- 20110411: renommage des séquences des tables renommées en 2.0rc12
-- -----------------------------------------------------------------------------

SELECT rename_sequence_ifexists( 'dossiers_rsa', 'dossiers' );
SELECT rename_sequence_ifexists( 'adresses_foyers', 'adressesfoyers' );
SELECT rename_sequence_ifexists( 'titres_sejour', 'titressejour' );
SELECT rename_sequence_ifexists( 'avispcgdroitrsa', 'avispcgdroitsrsa' );
SELECT rename_sequence_ifexists( 'ressourcesmensuelles_detailsressourcesmensuelles', 'detailsressourcesmensuelles_ressourcesmensuelles' );
SELECT rename_sequence_ifexists( 'typesaidesapres66_piecesaides66', 'piecesaides66_typesaidesapres66' );
SELECT rename_sequence_ifexists( 'typesaidesapres66_piecescomptables66', 'piecescomptables66_typesaidesapres66' );
SELECT rename_sequence_ifexists( 'users_contratsinsertion', 'contratsinsertion_users' );
SELECT rename_sequence_ifexists( 'zonesgeographiques_regroupementszonesgeo', 'regroupementszonesgeo_zonesgeographiques' );

-- -----------------------------------------------------------------------------------------------
-- 20110412: ajout de nouveaux champs sur la table decisionspropospdos suite à l'avis de l'EP
-- -----------------------------------------------------------------------------------------------
SELECT add_missing_table_field ('public', 'decisionspropospdos', 'hasreponseep', 'type_booleannumber');
SELECT add_missing_table_field ('public', 'decisionspropospdos', 'accordepaudition', 'type_booleannumber');
SELECT add_missing_table_field ('public', 'decisionspropospdos', 'commentairereponseep', 'TEXT');
SELECT add_missing_table_field ('public', 'decisionspropospdos', 'datereponseep', 'date');
SELECT add_missing_table_field ('public', 'decisionspropospdos', 'decisionreponseep', 'type_decisiondefautep66' );

-- -----------------------------------------------------------------------------------------------
-- 20110413: ajout de la structure référente pour les bilans de parcours provenant des sites partenaires
-- -----------------------------------------------------------------------------------------------
SELECT add_missing_table_field ('public', 'bilansparcours66', 'autrestructurereferente_id', 'integer' );
SELECT add_missing_constraint ('public', 'bilansparcours66', 'bilansparcours66_autrestructurereferente_id_fkey', 'structuresreferentes', 'autrestructurereferente_id');


-- -----------------------------------------------------------------------------------------------
-- 20110415: Ajout d'uhne table générique pour le stockage des fichiers scannés
-- -----------------------------------------------------------------------------------------------
DROP TABLE IF EXISTS fichiersmodules;

CREATE TABLE fichiersmodules (
    id                      SERIAL NOT NULL PRIMARY KEY,
    name                    VARCHAR(255) NOT NULL,
    fk_value                INTEGER NOT NULL,
    document                BYTEA DEFAULT NULL,
    modele                  VARCHAR(255) NOT NULL,
    cmspath                 VARCHAR(255) DEFAULT NULL,
    mime                    VARCHAR(255) NOT NULL,
    created                 TIMESTAMP WITHOUT TIME ZONE,
    modified                TIMESTAMP WITHOUT TIME ZONE
);

CREATE INDEX fichiersmodules_name_idx ON fichiersmodules( name );
CREATE INDEX fichiersmodules_fk_value_idx ON fichiersmodules( fk_value );
CREATE INDEX fichiersmodules_mime_idx ON fichiersmodules( mime );
CREATE UNIQUE INDEX fichiersmodules_cmspath_idx ON fichiersmodules( cmspath );


-- -----------------------------------------------------------------------------------------------
-- 20110418: Ajout d'un champ pour sélectionner si on ajoute des fichiers ou non aux PDOs
-- -----------------------------------------------------------------------------------------------
SELECT add_missing_table_field ('public', 'propospdos', 'haspiece', 'type_booleannumber');
ALTER TABLE propospdos ALTER COLUMN haspiece SET DEFAULT '0'::TYPE_BOOLEANNUMBER;
UPDATE propospdos SET haspiece = '0'::TYPE_BOOLEANNUMBER WHERE haspiece IS NULL;
ALTER TABLE propospdos ALTER COLUMN haspiece SET NOT NULL;

-- -----------------------------------------------------------------------------------------------
-- 20110418: Ajout d'une table de paramétrage pour les COVs du CG58
-- -----------------------------------------------------------------------------------------------
DROP TABLE IF EXISTS sitescovs58 CASCADE;
CREATE TABLE sitescovs58(
    id                      SERIAL NOT NULL PRIMARY KEY,
    name                    VARCHAR(255) NOT NULL
);
CREATE INDEX sitescovs58_name_idx ON sitescovs58( name );

SELECT add_missing_table_field ('public', 'covs58', 'sitecov58_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'covs58', 'covs58_sitecov58_id_fkey', 'sitescovs58', 'sitecov58_id');

-- Récupération des anciens noms de sites pré-saisis
-- FIXME; ne peut être passé qu'une fois pour le moment
INSERT INTO sitescovs58 ( name )
    SELECT
            covs58.name AS name
        FROM covs58
        WHERE
            covs58.name IS NOT NULL;

UPDATE covs58
    SET sitecov58_id = sitescovs58.id 
            FROM sitescovs58
            WHERE sitescovs58.name = covs58.name;

ALTER TABLE covs58 ALTER COLUMN sitecov58_id SET NOT NULL;
ALTER TABLE covs58 ALTER COLUMN name DROP NOT NULL;


-- -----------------------------------------------------------------------------------------------
-- 20110419: Ajout d'un champ pour sélectionner si on ajoute des fichiers ou non aux Orientations
-- -----------------------------------------------------------------------------------------------
SELECT add_missing_table_field ('public', 'orientsstructs', 'haspiecejointe', 'type_booleannumber');
ALTER TABLE orientsstructs ALTER COLUMN haspiecejointe SET DEFAULT '0'::TYPE_BOOLEANNUMBER;
UPDATE orientsstructs SET haspiecejointe = '0'::TYPE_BOOLEANNUMBER WHERE haspiecejointe IS NULL;
ALTER TABLE orientsstructs ALTER COLUMN haspiecejointe SET NOT NULL;
-- -----------------------------------------------------------------------------------------------
-- 20110419: Ajout d'un champ pour sélectionner si on ajoute des fichiers ou non aux Rendezvous
-- -----------------------------------------------------------------------------------------------
SELECT add_missing_table_field ('public', 'rendezvous', 'haspiecejointe', 'type_booleannumber');
ALTER TABLE rendezvous ALTER COLUMN haspiecejointe SET DEFAULT '0'::TYPE_BOOLEANNUMBER;
UPDATE rendezvous SET haspiecejointe = '0'::TYPE_BOOLEANNUMBER WHERE haspiecejointe IS NULL;
ALTER TABLE rendezvous ALTER COLUMN haspiecejointe SET NOT NULL;
-- -----------------------------------------------------------------------------------------------
-- 20110419: Ajout d'un champ pour sélectionner si on ajoute des fichiers ou non aux Bilans de Parcours 66
-- -----------------------------------------------------------------------------------------------
SELECT add_missing_table_field ('public', 'bilansparcours66', 'haspiecejointe', 'type_booleannumber');
ALTER TABLE bilansparcours66 ALTER COLUMN haspiecejointe SET DEFAULT '0'::TYPE_BOOLEANNUMBER;
UPDATE bilansparcours66 SET haspiecejointe = '0'::TYPE_BOOLEANNUMBER WHERE haspiecejointe IS NULL;
ALTER TABLE bilansparcours66 ALTER COLUMN haspiecejointe SET NOT NULL;
-- -----------------------------------------------------------------------------------------------
-- 20110419: Ajout d'un champ pour sélectionner si on ajoute des fichiers ou non aux CER
-- -----------------------------------------------------------------------------------------------
SELECT add_missing_table_field ('public', 'contratsinsertion', 'haspiecejointe', 'type_booleannumber');
ALTER TABLE contratsinsertion ALTER COLUMN haspiecejointe SET DEFAULT '0'::TYPE_BOOLEANNUMBER;
UPDATE contratsinsertion SET haspiecejointe = '0'::TYPE_BOOLEANNUMBER WHERE haspiecejointe IS NULL;
ALTER TABLE contratsinsertion ALTER COLUMN haspiecejointe SET NOT NULL;
-- *****************************************************************************
COMMIT;
-- *****************************************************************************