
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
----------------------------------------------------------------------------------------
-- 20130927 : Création d'une table de paramétrage pour les programmes en lien
--          avec les fiches de candidature région
----------------------------------------------------------------------------------------

DROP TABLE IF EXISTS progsfichescandidatures66 CASCADE;
CREATE TABLE progsfichescandidatures66(
    id                          SERIAL NOT NULL PRIMARY KEY,
    name                        VARCHAR(20) NOT NULL,
    isactif                     VARCHAR(1) NOT NULL DEFAULT '1',
	----------------------------------------------------------------------------
    created                     TIMESTAMP WITHOUT TIME ZONE,
    modified                    TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE progsfichescandidatures66 IS 'Table des différents programmes proposés par les fiches de candidature Région (CG66)';

DROP INDEX IF EXISTS progsfichescandidatures66_name_idx;
CREATE INDEX progsfichescandidatures66_name_idx ON progsfichescandidatures66( name );

DROP INDEX IF EXISTS progsfichescandidatures66_isactif_idx;
CREATE INDEX progsfichescandidatures66_isactif_idx ON progsfichescandidatures66( isactif );

SELECT alter_table_drop_constraint_if_exists( 'public', 'progsfichescandidatures66', 'progsfichescandidatures66_isactif_in_list_chk' );
ALTER TABLE progsfichescandidatures66 ADD CONSTRAINT progsfichescandidatures66_isactif_in_list_chk CHECK ( cakephp_validate_in_list( isactif, ARRAY['0', '1'] ) );

SELECT add_missing_table_field ( 'public', 'actionscandidats_personnes', 'formationregion', 'VARCHAR(250)' );

----------------------------------------------------------------------------------------
-- 20130927 : Création d'une table de liaison entre
--            les fiches de candidature et les programmes région
----------------------------------------------------------------------------------------
DROP TABLE IF EXISTS candidatures_progs66 CASCADE;
CREATE TABLE candidatures_progs66(
    id                              SERIAL NOT NULL PRIMARY KEY,
    actioncandidat_personne_id      INTEGER NOT NULL REFERENCES actionscandidats_personnes(id) ON DELETE CASCADE ON UPDATE CASCADE,
    progfichecandidature66_id       INTEGER NOT NULL REFERENCES progsfichescandidatures66(id) ON DELETE CASCADE ON UPDATE CASCADE,
	----------------------------------------------------------------------------
    created                     TIMESTAMP WITHOUT TIME ZONE,
    modified                    TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE candidatures_progs66 IS 'Table de liaison entre les fiches de candidature région et les programmes liés (CG66)';

DROP INDEX IF EXISTS candidatures_progs66_actioncandidat_personne_id_idx;
CREATE INDEX candidatures_progs66_actioncandidat_personne_id_idx ON candidatures_progs66( actioncandidat_personne_id );

DROP INDEX IF EXISTS candidatures_progs66_progfichecandidature66_id_idx;
CREATE INDEX candidatures_progs66_progfichecandidature66_id_idx ON candidatures_progs66( progfichecandidature66_id );

DROP INDEX IF EXISTS candidatures_progs66_actioncandidat_personne_id_progfichecandidature66_id_idx;
CREATE UNIQUE INDEX candidatures_progs66_actioncandidat_personne_id_progfichecandidature66_id_idx ON candidatures_progs66(actioncandidat_personne_id,progfichecandidature66_id);

-- *****************************************************************************
-- Module FSE, CG 93
-- *****************************************************************************

DROP TABLE IF EXISTS sortiesautresd2pdvs93 CASCADE;
CREATE TABLE sortiesautresd2pdvs93 (
	id			SERIAL NOT NULL PRIMARY KEY,
	name		VARCHAR(255) NOT NULL,
	created		TIMESTAMP WITHOUT TIME ZONE,
	modified	TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE sortiesautresd2pdvs93 IS 'Intitulés des motifs de sortie "Autres droits" du formulaire/tableau D2';

CREATE UNIQUE INDEX sortiesautresd2pdvs93_name_idx ON sortiesautresd2pdvs93( name );

--------------------------------------------------------------------------------

DROP TABLE IF EXISTS sortiesemploisd2pdvs93 CASCADE;
CREATE TABLE sortiesemploisd2pdvs93 (
	id			SERIAL NOT NULL PRIMARY KEY,
	name		VARCHAR(255) NOT NULL,
	created		TIMESTAMP WITHOUT TIME ZONE,
	modified	TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE sortiesemploisd2pdvs93 IS 'Intitulés des motifs de sortie "Emploi formation" du formulaire/tableau D2';

CREATE UNIQUE INDEX sortiesemploisd2pdvs93_name_idx ON sortiesemploisd2pdvs93( name );

--------------------------------------------------------------------------------

DROP TABLE IF EXISTS questionnairesd2pdvs93 CASCADE;
CREATE TABLE questionnairesd2pdvs93 (
    id								SERIAL NOT NULL PRIMARY KEY,
    personne_id						INTEGER NOT NULL REFERENCES personnes(id) ON DELETE CASCADE ON UPDATE CASCADE,
    structurereferente_id			INTEGER NOT NULL REFERENCES structuresreferentes(id) ON DELETE CASCADE ON UPDATE CASCADE,
	situationaccompagnement			VARCHAR(25) NOT NULL,
	sortieemploid2pdv93_id			INTEGER DEFAULT NULL REFERENCES sortiesemploisd2pdvs93(id) ON DELETE CASCADE ON UPDATE CASCADE,
	sortieautred2pdv93_id			INTEGER DEFAULT NULL REFERENCES sortiesautresd2pdvs93(id) ON DELETE CASCADE ON UPDATE CASCADE,
    created							TIMESTAMP WITHOUT TIME ZONE,
    modified						TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE questionnairesd2pdvs93 IS 'Réponses au formulaire D2 pour les PDV du CG 93';

CREATE INDEX questionnairesd2pdvs93_personne_id_idx ON questionnairesd2pdvs93( personne_id );
CREATE INDEX questionnairesd2pdvs93_structurereferente_id_idx ON questionnairesd2pdvs93( structurereferente_id );

SELECT alter_table_drop_constraint_if_exists( 'public', 'questionnairesd2pdvs93', 'questionnairesd2pdvs93_situationaccompagnement_in_list_chk' );
ALTER TABLE questionnairesd2pdvs93 ADD CONSTRAINT questionnairesd2pdvs93_situationaccompagnement_in_list_chk CHECK ( cakephp_validate_in_list( situationaccompagnement, ARRAY['sortie_obligation', 'abandon', 'reorientation', 'changement_situation', 'maintien'] ) );

--------------------------------------------------------------------------------
-- Tables supplémentaires à créer pour D1/D2
--------------------------------------------------------------------------------

DROP TABLE IF EXISTS populationsd1d2pdvs93 CASCADE;
CREATE TABLE populationsd1d2pdvs93 (
    id                          SERIAL NOT NULL PRIMARY KEY,
    questionnaired1pdv93_id		INTEGER DEFAULT NULL REFERENCES questionnairesd1pdvs93(id) ON DELETE CASCADE ON UPDATE CASCADE,
    questionnaired2pdv93_id		INTEGER DEFAULT NULL REFERENCES questionnairesd2pdvs93(id) ON DELETE CASCADE ON UPDATE CASCADE,
    tableausuivipdv93_id		INTEGER NOT NULL REFERENCES tableauxsuivispdvs93(id) ON DELETE CASCADE ON UPDATE CASCADE,
    created                     TIMESTAMP WITHOUT TIME ZONE,
    modified                    TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE populationsd1d2pdvs93 IS 'La population prise en compte pour les tableaux hisoricisés D1 ou D2';

-- On s'assure que soit questionnaired1pdv93_id, soit questionnaired2pdv93_id possède une valeur
SELECT alter_table_drop_constraint_if_exists( 'public', 'populationsd1d2pdvs93', 'populationsd1d2pdvs93_qd1_id_qd2_id_check' );
ALTER TABLE populationsd1d2pdvs93 ADD CONSTRAINT populationsd1d2pdvs93_qd1_id_qd2_id_check CHECK(
	( questionnaired1pdv93_id IS NOT NULL AND questionnaired2pdv93_id IS NULL )
	OR ( questionnaired1pdv93_id IS NULL AND questionnaired2pdv93_id IS NOT NULL )
);

--------------------------------------------------------------------------------
-- Modification des restrictions de la liste des tableaux de suivis PDV
--------------------------------------------------------------------------------

SELECT alter_table_drop_constraint_if_exists( 'public', 'tableauxsuivispdvs93', 'tableauxsuivispdvs93_name_in_list_chk' );
ALTER TABLE tableauxsuivispdvs93 ADD CONSTRAINT tableauxsuivispdvs93_name_in_list_chk CHECK ( cakephp_validate_in_list( name, ARRAY['tableaud1', 'tableaud2', 'tableau1b3', 'tableau1b4', 'tableau1b5', 'tableau1b6'] ) );

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
