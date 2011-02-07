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

DROP TABLE IF EXISTS themescovs58 CASCADE;
DROP TABLE IF EXISTS covs58 CASCADE;
DROP TABLE IF EXISTS dossierscovs58 CASCADE;
DROP TABLE IF EXISTS proposorientationscovs58 CASCADE;
DROP TABLE IF EXISTS proposcontratsinsertioncovs58 CASCADE;

-- *****************************************************************************

DROP TYPE IF EXISTS TYPE_ETATCOV CASCADE;
DROP TYPE IF EXISTS TYPE_ETAPECOV CASCADE;

-- *****************************************************************************

CREATE TABLE themescovs58 (
	id					SERIAL NOT NULL PRIMARY KEY,
	name				VARCHAR(50) NOT NULL
);
COMMENT ON TABLE themescovs58 IS 'Liste des différents thèmes traités par la COV (cg58)';

CREATE TYPE TYPE_ETATCOV AS ENUM ('cree', 'traitement', 'finalise');

CREATE TABLE covs58 (
	id					SERIAL NOT NULL PRIMARY KEY,
	name				VARCHAR(50) NOT NULL,
	lieu				VARCHAR(100) DEFAULT NULL,
	datecommission		TIMESTAMP WITHOUT TIME ZONE NOT NULL,
	observation			TEXT DEFAULT NULL,
	etatcov				TYPE_ETATCOV NOT NULL DEFAULT 'cree'
);
COMMENT ON TABLE covs58 IS 'Commissions de la COV (cg58)';

CREATE TYPE TYPE_ETAPECOV AS ENUM ('cree', 'traitement', 'ajourne', 'finalise');

CREATE TABLE dossierscovs58 (
	id 							SERIAL NOT NULL PRIMARY KEY,
	personne_id 				INTEGER NOT NULL REFERENCES personnes(id) ON DELETE CASCADE ON UPDATE CASCADE,
	themecov58_id				INTEGER NOT NULL REFERENCES themescovs58(id) ON DELETE CASCADE ON UPDATE CASCADE,
	etapecov					TYPE_ETAPECOV NOT NULL DEFAULT 'cree',
	cov58_id					INTEGER DEFAULT NULL REFERENCES covs58(id) ON DELETE CASCADE ON UPDATE CASCADE
);
COMMENT ON TABLE dossierscovs58 IS 'Dossiers en attente de validation par la COV (cg58)';

CREATE INDEX dossierscovs58_personne_id_idx ON dossierscovs58(personne_id);
CREATE INDEX dossierscovs58_themecov58_id_idx ON dossierscovs58(themecov58_id);
CREATE INDEX dossierscovs58_cov58_id_idx ON dossierscovs58(cov58_id);

CREATE TABLE proposorientationscovs58 (
	id 							SERIAL NOT NULL PRIMARY KEY,
	dossiercov58_id				INTEGER NOT NULL REFERENCES dossierscovs58(id) ON DELETE CASCADE ON UPDATE CASCADE,
	typeorient_id 				INTEGER NOT NULL REFERENCES typesorients(id) ON DELETE CASCADE ON UPDATE CASCADE,
	structurereferente_id 		INTEGER NOT NULL REFERENCES structuresreferentes(id) ON DELETE CASCADE ON UPDATE CASCADE,
	referent_id 				INTEGER DEFAULT NULL REFERENCES referents(id) ON DELETE CASCADE ON UPDATE CASCADE,
	datedemande 				DATE NOT NULL,
	rgorient 					INTEGER NOT NULL,
	covtypeorient_id 			INTEGER DEFAULT NULL REFERENCES typesorients(id) ON DELETE CASCADE ON UPDATE CASCADE,
	covstructurereferente_id	INTEGER DEFAULT NULL REFERENCES structuresreferentes(id) ON DELETE CASCADE ON UPDATE CASCADE,
	datevalidation				DATE DEFAULT NULL,
	commentaire 				TEXT DEFAULT NULL
);
COMMENT ON TABLE proposorientationscovs58 IS 'Orientations en attente de validation par la COV (cg58)';
-- ajouter contrainte que pour une personne que l'on n'ait qu'une seule proposition non traitée

CREATE INDEX proposorientationscovs58_dossiercov58_id_idx ON proposorientationscovs58(dossiercov58_id);
CREATE INDEX proposorientationscovs58_typeorient_id_idx ON proposorientationscovs58(typeorient_id);
CREATE INDEX proposorientationscovs58_structurereferente_id_idx ON proposorientationscovs58(structurereferente_id);
CREATE INDEX proposorientationscovs58_referent_id_idx ON proposorientationscovs58(referent_id);
CREATE INDEX proposorientationscovs58_covtypeorient_id_idx ON proposorientationscovs58(covtypeorient_id);
CREATE INDEX proposorientationscovs58_covstructurereferente_id_idx ON proposorientationscovs58(covstructurereferente_id);

CREATE TABLE proposcontratsinsertioncovs58 (
	id 							SERIAL NOT NULL PRIMARY KEY,
	dossiercov58_id				INTEGER NOT NULL REFERENCES dossierscovs58(id) ON DELETE CASCADE ON UPDATE CASCADE,
	structurereferente_id 		INTEGER NOT NULL REFERENCES structuresreferentes(id) ON DELETE CASCADE ON UPDATE CASCADE,
	referent_id 				INTEGER DEFAULT NULL REFERENCES referents(id) ON DELETE CASCADE ON UPDATE CASCADE,
	datedemande 				DATE NOT NULL,
	num_contrat					TYPE_NUM_CONTRAT NOT NULL,
	dd_ci						DATE NOT NULL,
	duree_engag					INTEGER NOT NULL,
	df_ci						DATE NOT NULL,
	forme_ci					VARCHAR(1) NOT NULL,
	avisraison_ci				VARCHAR(1) DEFAULT NULL,
	rg_ci						INTEGER DEFAULT NULL,
	datevalidation				DATE DEFAULT NULL,
	commentaire 				TEXT DEFAULT NULL
);
COMMENT ON TABLE proposcontratsinsertioncovs58 IS 'Contrats d''insertion en attente de validation par la COV (cg58)';
-- ajouter contrainte que pour une personne que l'on n'ait qu'une seule proposition non traitée

CREATE INDEX proposcontratsinsertioncovs58_dossiercov58_id_idx ON proposcontratsinsertioncovs58(dossiercov58_id);
CREATE INDEX proposcontratsinsertioncovs58_structurereferente_id_idx ON proposcontratsinsertioncovs58(structurereferente_id);
CREATE INDEX proposcontratsinsertioncovs58_referent_id_idx ON proposcontratsinsertioncovs58(referent_id);

-- *****************************************************************************
COMMIT;
-- *****************************************************************************