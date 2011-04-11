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
-- *****************************************************************************
COMMIT;
-- *****************************************************************************