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

DROP TABLE IF EXISTS proposorientationscovs58 CASCADE;

-- *****************************************************************************

DROP TYPE IF EXISTS TYPE_ETAPECOV CASCADE;

-- *****************************************************************************

CREATE TYPE TYPE_ETAPECOV AS ENUM ('cree', 'traitement', 'finalise');

CREATE TABLE proposorientationscovs58 (
	id 							SERIAL NOT NULL PRIMARY KEY,
	personne_id 				INTEGER NOT NULL REFERENCES personnes(id) ON DELETE CASCADE ON UPDATE CASCADE,
	typeorient_id 				INTEGER NOT NULL REFERENCES typesorients(id) ON DELETE CASCADE ON UPDATE CASCADE,
	structurereferente_id 		INTEGER NOT NULL REFERENCES structuresreferentes(id) ON DELETE CASCADE ON UPDATE CASCADE,
	referent_id 				INTEGER DEFAULT NULL REFERENCES referents(id) ON DELETE CASCADE ON UPDATE CASCADE,
	datedemande 				DATE NOT NULL,
	datevalidation 				DATE DEFAULT NULL,
	covtypeorient_id 			INTEGER DEFAULT NULL REFERENCES typesorients(id) ON DELETE CASCADE ON UPDATE CASCADE,
	covstructurereferente_id 	INTEGER DEFAULT NULL REFERENCES structuresreferentes(id) ON DELETE CASCADE ON UPDATE CASCADE,
	commentaire 				TEXT DEFAULT NULL,
	rgorient 					INTEGER NOT NULL,
	etapecov					TYPE_ETAPECOV NOT NULL DEFAULT 'cree'
);
COMMENT ON TABLE proposorientationscovs58 IS 'Orientations en attente de validation par la COV (cg58)';
-- ajouter contrainte que pour une personne que l'on n'ait qu'une seule proposition non traitée

CREATE INDEX proposorientationscovs58_personne_id_idx ON proposorientationscovs58(personne_id);
CREATE INDEX proposorientationscovs58_typeorient_id_idx ON proposorientationscovs58(typeorient_id);
CREATE INDEX proposorientationscovs58_structurereferente_id_idx ON proposorientationscovs58(structurereferente_id);
CREATE INDEX proposorientationscovs58_referent_id_idx ON proposorientationscovs58(referent_id);
CREATE INDEX proposorientationscovs58_covtypeorient_id_idx ON proposorientationscovs58(covtypeorient_id);
CREATE INDEX proposorientationscovs58_covstructurereferente_id_idx ON proposorientationscovs58(covstructurereferente_id);

-- CREATE TABLE proposcontratsinsertion58 (
-- 	id SERIAL NOT NULL PRIMARY KEY
-- );

-- *****************************************************************************
COMMIT;
-- *****************************************************************************