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
-- TODO: supprimer la colonne nonrespectssanctionseps93.sortienvcontrat (cf. patch 2.9.02)
-- *****************************************************************************

SELECT alter_table_drop_constraint_if_exists( 'public', 'dossiers', 'dossiers_fonorg_in_list_chk' );
ALTER TABLE dossiers ADD CONSTRAINT dossiers_fonorg_in_list_chk CHECK ( cakephp_validate_in_list( fonorg, ARRAY[ 'CAF', 'MSA' ] ) );

--------------------------------------------------------------------------------
-- 20150710: Champs "enum" de la table orientsstructs
-- @todo: le reste des 119 tables -> SELECT DISTINCT table_name FROM information_schema.columns WHERE data_type = 'USER-DEFINED' ORDER BY table_name;
--------------------------------------------------------------------------------
ALTER TABLE orientsstructs DROP CONSTRAINT orientsstructs_origine_check;

-- FIXME: suppression du schéma administration (CASCADE) dans la BDD
-- cg93_20150608_v300 car il existe une règle prenant origine en compte dans la
-- vue allocatairestransferes, ce qui empêche la transformation.
SELECT public.table_enumtypes_to_validate_in_list( 'public', 'orientsstructs' );
SELECT public.table_defaultvalues_enumtypes_to_varchar( 'public', 'orientsstructs' );

ALTER TABLE orientsstructs ADD CONSTRAINT orientsstructs_origine_check CHECK(
	( origine IS NULL AND date_valid IS NULL )
	OR (
		( origine IS NOT NULL AND date_valid IS NOT NULL )
		AND (
			( rgorient = 1 AND origine IN ( 'manuelle', 'cohorte' ) )
			OR ( rgorient > 1 AND origine = 'reorientation' )
			OR ( rgorient > 1 AND origine = 'demenagement' )
		)
	)
);

SELECT alter_table_drop_constraint_if_exists( 'public', 'orientsstructs', 'orientsstructs_statutrelance_in_list_chk' );
ALTER TABLE orientsstructs ADD CONSTRAINT orientsstructs_statutrelance_in_list_chk CHECK ( cakephp_validate_in_list( statutrelance, ARRAY[ 'E', 'R' ] ) );

SELECT alter_table_drop_constraint_if_exists( 'public', 'orientsstructs', 'orientsstructs_statut_orient_in_list_chk' );
ALTER TABLE orientsstructs ADD CONSTRAINT orientsstructs_statut_orient_in_list_chk CHECK ( cakephp_validate_in_list( statut_orient, ARRAY[ 'Orienté', 'En attente', 'Non orienté' ] ) );

ALTER TABLE orientsstructs ALTER COLUMN etatorient SET DEFAULT NULL;

--------------------------------------------------------------------------------
-- 20150723: Champs "enum" de la table foyers
-- @todo >(sitfam|typeocclog)
--------------------------------------------------------------------------------

SELECT public.table_enumtypes_to_validate_in_list( 'public', 'foyers' );
SELECT public.table_defaultvalues_enumtypes_to_varchar( 'public', 'foyers' );

-- Certaines entrées avaient une chaîne de texte vide à la place de NULL
-- FIXME: le signaler pour les jobs talend et vérifier à la sauvegarde dans l'application
UPDATE foyers SET sitfam = NULL WHERE TRIM( BOTH ' ' FROM sitfam ) = '';
SELECT alter_table_drop_constraint_if_exists( 'public', 'foyers', 'foyers_sitfam_in_list_chk' );
ALTER TABLE foyers ADD CONSTRAINT foyers_sitfam_in_list_chk CHECK ( cakephp_validate_in_list( sitfam, ARRAY[ 'ABA', 'CEL', 'DIV', 'ISO', 'MAR', 'PAC', 'RPA', 'RVC', 'RVM', 'SEF', 'SEL', 'VEU', 'VIM' ] ) );
ALTER TABLE foyers ALTER COLUMN sitfam SET DEFAULT NULL;

-- Certaines entrées avaient une chaîne de texte vide à la place de NULL
-- FIXME: le signaler pour les jobs talend et vérifier à la sauvegarde dans l'application
UPDATE foyers SET typeocclog = NULL WHERE TRIM( BOTH ' ' FROM typeocclog ) = '';
SELECT alter_table_drop_constraint_if_exists( 'public', 'foyers', 'foyers_typeocclog_in_list_chk' );
ALTER TABLE foyers ADD CONSTRAINT foyers_typeocclog_in_list_chk CHECK ( cakephp_validate_in_list( typeocclog, ARRAY[ 'ACC', 'BAL', 'HCG', 'HCO', 'HGP', 'HOP', 'HOT', 'LOC', 'OLI', 'PRO', 'SRG', 'SRO' ] ) );
ALTER TABLE foyers ALTER COLUMN typeocclog SET DEFAULT NULL;


--------------------------------------------------------------------------------
-- Canton
--------------------------------------------------------------------------------

DROP TABLE IF EXISTS adresses_cantons;
CREATE TABLE adresses_cantons (
	id SERIAL NOT NULL PRIMARY KEY,
	adresse_id INTEGER NOT NULL REFERENCES adresses(id) ON DELETE CASCADE ON UPDATE CASCADE,
	canton_id INTEGER NOT NULL REFERENCES cantons(id) ON DELETE CASCADE ON UPDATE CASCADE
);
ALTER TABLE adresses_cantons ADD CONSTRAINT adresses_cantons_unique_adresse_id_canton_id UNIQUE ( adresse_id );


--------------------------------------------------------------------------------
-- Marquage Tag
--------------------------------------------------------------------------------

DROP TABLE IF EXISTS categorietags CASCADE;
CREATE TABLE categorietags (
	id SERIAL			NOT NULL PRIMARY KEY,
	name				VARCHAR(255) NOT NULL,
	created				TIMESTAMP WITHOUT TIME ZONE,
    modified			TIMESTAMP WITHOUT TIME ZONE
);

DROP TABLE IF EXISTS valeurstags CASCADE;
CREATE TABLE valeurstags (
	id SERIAL			NOT NULL PRIMARY KEY,
	name				VARCHAR(255) NOT NULL,
	categorietag_id		INTEGER DEFAULT NULL REFERENCES categorietags(id) ON DELETE CASCADE ON UPDATE CASCADE,
	created				TIMESTAMP WITHOUT TIME ZONE,
    modified			TIMESTAMP WITHOUT TIME ZONE
);

DROP TABLE IF EXISTS tags CASCADE;
CREATE TABLE tags (
	id SERIAL			NOT NULL PRIMARY KEY,
	fk_value			INTEGER NOT NULL,
	modele				VARCHAR(255) NOT NULL,
	valeurtag_id		INTEGER DEFAULT NULL REFERENCES valeurstags(id) ON DELETE CASCADE ON UPDATE CASCADE,
	etat				VARCHAR(255) DEFAULT 'encours',
	commentaire			TEXT,
	limite				DATE,
	created				TIMESTAMP WITHOUT TIME ZONE,
    modified			TIMESTAMP WITHOUT TIME ZONE
);
ALTER TABLE tags ADD CONSTRAINT tags_etat_in_list_chk CHECK ( cakephp_validate_in_list( etat, ARRAY[ 'traite', 'annule', 'perime', 'encours' ] ) );


--------------------------------------------------------------------------------
-- Conservation des données d'impression
--------------------------------------------------------------------------------

DROP TABLE IF EXISTS dataimpressions;
CREATE TABLE dataimpressions (
	id SERIAL			NOT NULL PRIMARY KEY,
	fk_value			INTEGER NOT NULL,
	modele				VARCHAR(255) NOT NULL,
	data				TEXT,
	created				TIMESTAMP WITHOUT TIME ZONE,
    modified			TIMESTAMP WITHOUT TIME ZONE
);

--------------------------------------------------------------------------------
-- Actions région - Catégories et valeurs
--------------------------------------------------------------------------------

DROP TABLE IF EXISTS valsprogsfichescandidatures66;
CREATE TABLE valsprogsfichescandidatures66 (
	id SERIAL					NOT NULL PRIMARY KEY,
	name						VARCHAR(255) NOT NULL,
	progfichecandidature66_id	INTEGER NOT NULL REFERENCES progsfichescandidatures66(id) ON DELETE CASCADE ON UPDATE CASCADE,
	actif						SMALLINT,
	created						TIMESTAMP WITHOUT TIME ZONE,
	modified					TIMESTAMP WITHOUT TIME ZONE
);
ALTER TABLE valsprogsfichescandidatures66 ADD CONSTRAINT valsprogsfichescandidatures66_actif_in_list_chk CHECK ( cakephp_validate_in_list( actif, ARRAY[0,1] ) );

SELECT alter_table_drop_column_if_exists( 'public', 'actionscandidats_personnes', 'valprogfichecandidature66_id' );

ALTER TABLE actionscandidats_personnes ADD COLUMN valprogfichecandidature66_id INTEGER REFERENCES valsprogsfichescandidatures66(id) ON DELETE SET NULL ON UPDATE CASCADE;

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
