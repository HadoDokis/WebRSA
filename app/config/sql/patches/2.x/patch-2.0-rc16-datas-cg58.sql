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

TRUNCATE informationspe CASCADE;
TRUNCATE historiqueetatspe CASCADE;

-- *****************************************************************************

INSERT INTO informationspe ( id, nir, nom, prenom, dtnai ) VALUES
	( 1, NULL, 'SEMENCE', 'BRUNO', '1962-12-19' ),
	( 2, NULL, 'VRIGNAT', 'DOLORES', '1956-10-01' ),
	( 3, NULL, 'CHATELET', 'SYLVIE', '1964-02-13' ),
	( 4, NULL, 'VINADELLE', 'COLETTE', '1951-08-22' ),
	( 5, NULL, 'DESBRANCHES', 'MARCEL', '1952-03-18' )
;

INSERT INTO historiqueetatspe ( informationpe_id, identifiantpe, date, etat ) VALUES
	( 1, '0611044290Y', '2011-01-01', 'radiation' ),
	( 2, '0611717975P', '2011-01-01', 'radiation' ),
	( 3, '0613061080L', '2011-01-01', 'radiation' ),
	( 4, '0611309465G', '2011-01-01', 'radiation' ),
	( 5, '0610905944X', '2011-01-01', 'radiation' )
;

-- *****************************************************************************
COMMIT;
-- *****************************************************************************