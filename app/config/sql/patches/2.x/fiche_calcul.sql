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

DROP TABLE IF EXISTS fichescalculs CASCADE;

DROP TYPE IF EXISTS TYPE_REGIMEFICHECALCUL CASCADE;

-- *****************************************************************************

CREATE TYPE TYPE_REGIMEFICHECALCUL AS ENUM ( 'fagri', 'ragri', 'reel', 'microbic', 'microbnc' );

CREATE TABLE fichescalculs (
	id						SERIAL NOT NULL PRIMARY KEY,
	name					TYPE_REGIMEFICHECALCUL NOT NULL,
	saisonnier				TYPE_BOOLEANNUMBER,
	nrmrcs					VARCHAR(20) NOT NULL,
	dtdebutactivite			DATE NOT NULL,
	raisonsocial			VARCHAR(100) NOT NULL,
	dtdebutperiode			DATE NOT NULL,
	dtfinperiode			DATE NOT NULL,
	nbmoisactivite			INTEGER NOT NULL,
	mnttotal				FLOAT,
	revenus					FLOAT,
	dtprisecompte			DATE NOT NULL,
	dtecheance				DATE NOT NULL,
	forfait					FLOAT,
	mtaidesub				FLOAT,
	chaffvnt				FLOAT,
	chaffsrv				FLOAT,
	benefoudef				FLOAT,
	benefpriscompte			FLOAT,
	ammortissements			FLOAT,
	salaireexploitant		FLOAT,
	provisionsnonded		FLOAT,
	moinsvaluescession		FLOAT,
	autrecorrection			FLOAT,
	traitementpdo_id		INTEGER NOT NULL REFERENCES traitementspdos(id)
);

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
