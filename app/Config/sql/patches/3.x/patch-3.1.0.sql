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

--------------------------------------------------------------------------------
-- CUI -
--------------------------------------------------------------------------------

SELECT alter_table_drop_column_if_exists( 'public', 'cuis66', 'commentaireformation' );
ALTER TABLE cuis66 ADD COLUMN commentaireformation TEXT;

SELECT alter_table_drop_column_if_exists( 'public', 'partenairescuis66', 'activiteprincipale' );
ALTER TABLE partenairescuis66 ADD COLUMN activiteprincipale VARCHAR(255);

SELECT alter_table_drop_column_if_exists( 'public', 'cuis', 'decision_cui' );
ALTER TABLE cuis ADD COLUMN decision_cui VARCHAR(1);

ALTER TABLE cuis ADD CONSTRAINT cuis_decision_ci_in_list_chk CHECK ( cakephp_validate_in_list( decision_cui, ARRAY['A','E','V','R'] ) );

--------------------------------------------------------------------------------
-- TAG -
--------------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION create_entites_tags() RETURNS void AS
$$
BEGIN

    IF NOT EXISTS(SELECT * FROM pg_catalog.pg_tables 
			WHERE  schemaname = 'public'
			AND    tablename  = 'entites_tags') THEN 

        CREATE TABLE entites_tags (
			id					SERIAL NOT NULL PRIMARY KEY,
			tag_id				INTEGER NOT NULL REFERENCES tags(id) ON DELETE CASCADE ON UPDATE CASCADE,
			fk_value			INTEGER NOT NULL,
			modele				VARCHAR(255) NOT NULL
		);

		INSERT INTO entites_tags (tag_id, fk_value, modele) (SELECT tags.id, tags.fk_value, tags.modele FROM tags);

		ALTER TABLE tags DROP COLUMN fk_value;
		ALTER TABLE tags DROP COLUMN modele;

    END IF;

END;
$$
LANGUAGE 'plpgsql';

SELECT create_entites_tags();
DROP FUNCTION create_entites_tags();

SELECT alter_table_drop_constraint_if_exists ( 'public', 'entites_tags', 'entites_tags_fk_value_modele_unique' );
ALTER TABLE entites_tags ADD CONSTRAINT entites_tags_fk_value_modele_unique UNIQUE (tag_id, fk_value, modele);

--------------------------------------------------------------------------------
-- SaveSearch -
--------------------------------------------------------------------------------

DROP TABLE IF EXISTS savesearchs;
CREATE TABLE savesearchs (
	id SERIAL NOT NULL PRIMARY KEY,
	user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
	group_id INTEGER NOT NULL REFERENCES groups(id) ON DELETE CASCADE ON UPDATE CASCADE,
	isforgroup SMALLINT NOT NULL DEFAULT 0,
	isformenu SMALLINT NOT NULL DEFAULT 0,
	name VARCHAR(255) NOT NULL,
	url TEXT NOT NULL,
	controller VARCHAR(255) NOT NULL,
	action VARCHAR(255) NOT NULL,
	created TIMESTAMP WITHOUT TIME ZONE,
    modified TIMESTAMP WITHOUT TIME ZONE
);
ALTER TABLE savesearchs ADD CONSTRAINT savesearchs_isforgroup_in_list_chk CHECK ( cakephp_validate_in_list( isforgroup, ARRAY[0, 1] ) );
ALTER TABLE savesearchs ADD CONSTRAINT savesearchs_isformenu_in_list_chk CHECK ( cakephp_validate_in_list( isformenu, ARRAY[0, 1] ) );

--------------------------------------------------------------------------------
-- Fiche de liaison - Paramêtrages
--------------------------------------------------------------------------------

DROP TABLE IF EXISTS avisprimoanalyses, primoanalyses, fichedeliaisons_personnes, avisfichedeliaisons, logicielprimos_primoanalyses, fichedeliaisons, motiffichedeliaisons;
CREATE TABLE motiffichedeliaisons (
	id SERIAL NOT NULL PRIMARY KEY,
	name VARCHAR(255),
	actif SMALLINT
);
ALTER TABLE motiffichedeliaisons ADD CONSTRAINT motiffichedeliaisons_actif_in_list_chk CHECK ( cakephp_validate_in_list( actif, ARRAY[0, 1] ) );

DROP TABLE IF EXISTS logicielprimos;
CREATE TABLE logicielprimos (
	id SERIAL NOT NULL PRIMARY KEY,
	name VARCHAR(255),
	actif SMALLINT
);
ALTER TABLE logicielprimos ADD CONSTRAINT logicielprimos_actif_in_list_chk CHECK ( cakephp_validate_in_list( actif, ARRAY[0, 1] ) );

DROP TABLE IF EXISTS propositionprimos;
CREATE TABLE propositionprimos (
	id SERIAL NOT NULL PRIMARY KEY,
	name VARCHAR(255),
	actif SMALLINT
);
ALTER TABLE propositionprimos ADD CONSTRAINT propositionprimos_actif_in_list_chk CHECK ( cakephp_validate_in_list( actif, ARRAY[0, 1] ) );

--------------------------------------------------------------------------------
-- Fiche de liaison - Tables principales
--------------------------------------------------------------------------------

CREATE TABLE fichedeliaisons (
	id SERIAL NOT NULL PRIMARY KEY,
	foyer_id INTEGER NOT NULL REFERENCES foyers(id) ON DELETE CASCADE ON UPDATE CASCADE,
	motiffichedeliaison_id INTEGER NOT NULL REFERENCES motiffichedeliaisons(id) ON DELETE CASCADE ON UPDATE CASCADE,
	expediteur_id INTEGER NOT NULL REFERENCES originespdos(id) ON DELETE CASCADE ON UPDATE CASCADE,
	destinataire_id INTEGER NOT NULL REFERENCES originespdos(id) ON DELETE CASCADE ON UPDATE CASCADE,
	user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
	datefiche DATE NOT NULL,
	commentaire TEXT,
	etat VARCHAR(16),
	haspiecejointe CHAR(1) NOT NULL DEFAULT '0',
	created TIMESTAMP WITHOUT TIME ZONE,
    modified TIMESTAMP WITHOUT TIME ZONE
);
ALTER TABLE fichedeliaisons ADD CONSTRAINT fichedeliaisons_etape_in_list_chk CHECK ( cakephp_validate_in_list( etat, ARRAY['attavistech', 'attval', 'decisionnonvalid', 'decisionvalid', 'traite', 'annule'] ) );

CREATE TABLE primoanalyses (
	id SERIAL NOT NULL PRIMARY KEY,
	fichedeliaison_id INTEGER NOT NULL REFERENCES fichedeliaisons(id) ON DELETE CASCADE ON UPDATE CASCADE,
	user_id INTEGER REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE, -- Gestionnaire, potentiellement != fichedeliaisons
	dossierpcg66_id INTEGER REFERENCES dossierspcgs66(id) ON DELETE SET NULL ON UPDATE CASCADE,
	propositionprimo_id INTEGER REFERENCES propositionprimos(id) ON DELETE CASCADE ON UPDATE CASCADE,
	createdossierpcg SMALLINT,
	dateaffectation DATE,
	dateprimo DATE,
	commentaire TEXT,
	etat VARCHAR(16),
	created TIMESTAMP WITHOUT TIME ZONE,
    modified TIMESTAMP WITHOUT TIME ZONE
);
ALTER TABLE primoanalyses ADD CONSTRAINT primoanalyses_etape_in_list_chk CHECK ( cakephp_validate_in_list( etat, ARRAY['attaffect', 'attinstr', 'attavistech', 'attval', 'decisionnonvalid', 'traite', 'annule'] ) );
ALTER TABLE primoanalyses ADD CONSTRAINT primoanalyses_createdossierpcg_in_list_chk CHECK ( cakephp_validate_in_list( createdossierpcg, ARRAY[0, 1] ) );

--------------------------------------------------------------------------------
-- Fiche de liaison - Tables de liaisons
--------------------------------------------------------------------------------

CREATE TABLE fichedeliaisons_personnes (
	id SERIAL NOT NULL PRIMARY KEY,
	fichedeliaison_id INTEGER NOT NULL REFERENCES fichedeliaisons(id) ON DELETE CASCADE ON UPDATE CASCADE,
	personne_id INTEGER NOT NULL REFERENCES personnes(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE logicielprimos_primoanalyses (
	id SERIAL NOT NULL PRIMARY KEY,
	logicielprimo_id INTEGER NOT NULL REFERENCES logicielprimos(id) ON DELETE CASCADE ON UPDATE CASCADE,
	primoanalyse_id INTEGER NOT NULL REFERENCES primoanalyses(id) ON DELETE CASCADE ON UPDATE CASCADE,
	consultation DATE,
	commentaire TEXT
);

--------------------------------------------------------------------------------
-- Fiche de liaison - Avis et validations
--------------------------------------------------------------------------------

CREATE TABLE avisfichedeliaisons (
	id SERIAL NOT NULL PRIMARY KEY,
	fichedeliaison_id INTEGER NOT NULL REFERENCES fichedeliaisons(id) ON DELETE CASCADE ON UPDATE CASCADE,
	etape VARCHAR(10),
	date DATE,
	choix SMALLINT NOT NULL,
	commentaire TEXT,
	user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
	created TIMESTAMP WITHOUT TIME ZONE,
    modified TIMESTAMP WITHOUT TIME ZONE
);
ALTER TABLE avisfichedeliaisons ADD CONSTRAINT avisfichedeliaisons_etape_in_list_chk CHECK ( cakephp_validate_in_list( etape, ARRAY['avis', 'validation'] ) );
ALTER TABLE avisfichedeliaisons ADD CONSTRAINT avisfichedeliaisons_choix_in_list_chk CHECK ( cakephp_validate_in_list( choix, ARRAY[0, 1] ) );

CREATE TABLE avisprimoanalyses (
	id SERIAL NOT NULL PRIMARY KEY,
	primoanalyse_id INTEGER NOT NULL REFERENCES primoanalyses(id) ON DELETE CASCADE ON UPDATE CASCADE,
	etape VARCHAR(10),
	date DATE,
	choix SMALLINT NOT NULL,
	commentaire TEXT,
	user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
	created TIMESTAMP WITHOUT TIME ZONE,
    modified TIMESTAMP WITHOUT TIME ZONE
);
ALTER TABLE avisprimoanalyses ADD CONSTRAINT avisprimoanalyses_etape_in_list_chk CHECK ( cakephp_validate_in_list( etape, ARRAY['avis', 'validation'] ) );
ALTER TABLE avisprimoanalyses ADD CONSTRAINT avisprimoanalyses_choix_in_list_chk CHECK ( cakephp_validate_in_list( choix, ARRAY[0, 1] ) );

-- *****************************************************************************
COMMIT;
-- *****************************************************************************