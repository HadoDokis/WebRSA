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

INSERT INTO regroupementseps ( name ) VALUES
	( 'CLI 1' );

INSERT INTO eps ( name, regroupementep_id, saisineepreorientsr93 ) VALUES
	( 'CLI 1, équipe 1.1', 1, 'ep' );

INSERT INTO fonctionsmembreseps ( name ) VALUES
	( 'Chef de projet de ville' ),
	( 'Représentant de Pôle Emploi' ),
	( 'Chargé d''insertion' );

INSERT INTO membreseps ( ep_id, fonctionmembreep_id, qual, nom, prenom ) VALUES
	( 1, 1, 'Mlle.', 'Dupont', 'Anne' ),
	( 1, 1, 'M.', 'Martin', 'Pierre' ),
	( 1, 2, 'M.', 'Dubois', 'Alphonse' ),
	( 1, 2, 'Mme.', 'Roland', 'Adeline' );

INSERT INTO eps_zonesgeographiques ( ep_id, zonegeographique_id ) VALUES
	( 1, 14 ), -- EPINAY-SUR-SEINE
	( 1, 31 ), -- PIERREFITTE-SUR-SEINE
	( 1, 36 ); -- SAINT-OUEN

INSERT INTO motifsreorients ( name ) VALUES
	( 'Motif réorientation 1' ),
	( 'Motif réorientation 2' );

SELECT pg_catalog.setval('seanceseps_id_seq', 1, true);
INSERT INTO seanceseps VALUES ( 1, 1, 25, '2010-10-28 10:00:00', NULL );

-- *****************************************************************************
COMMIT;
-- *****************************************************************************