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
-- INFO: attention à ne pas passer ce morceau plusieurs fois!
--------------------------------------------------------------------------------
CREATE OR REPLACE FUNCTION public.update_duree_engag_integer( p_table TEXT ) RETURNS VOID AS
$$
	DECLARE
		v_query text;
	BEGIN
		v_query := 'UPDATE ' || p_table || '
			SET duree_engag =
				CASE
					WHEN duree_engag = 6 THEN 24
					WHEN duree_engag = 5 THEN 18
					WHEN duree_engag = 4 THEN 12
					WHEN duree_engag = 3 THEN 9
					WHEN duree_engag = 2 THEN 6
					WHEN duree_engag = 1 THEN 3
					ELSE 999
				END
			WHERE duree_engag IS NOT NULL;';

		RAISE NOTICE  '%', v_query;
		EXECUTE v_query;
	END;
$$
LANGUAGE plpgsql;

DO LANGUAGE plpgsql $$ DECLARE
BEGIN

IF NOT EXISTS( select tablename from pg_tables where tablename = 'version' )
THEN

SELECT public.update_duree_engag_integer( 'bilansparcours66' );
SELECT public.update_duree_engag_integer( 'contratsinsertion' );
SELECT public.update_duree_engag_integer( 'proposcontratsinsertioncovs58' );
SELECT public.update_duree_engag_integer( 'decisionsproposcontratsinsertioncovs58' );

END IF;
END $$;

DROP FUNCTION public.update_duree_engag_integer( p_table TEXT );

--------------------------------------------------------------------------------
-- 20150403: Création de deux nouvelles thématiques de COV pour le CG 58:
--           nonorientationsproscovs58 et regressionsorientationscovs58.
-- -> decisionsnonorientationsproscovs58, decisionsregressionsorientationscovs58
--------------------------------------------------------------------------------

SELECT alter_enumtype( 'TYPE_THEMECOV58', ARRAY['proposorientationscovs58','proposcontratsinsertioncovs58','proposnonorientationsproscovs58','nonorientationsproscovs58','nonorientationsproscovs58','regressionsorientationscovs58']);

INSERT INTO themescovs58 ( name ) VALUES
	( 'nonorientationsproscovs58' ),
	( 'regressionsorientationscovs58' );

SELECT add_missing_table_field ( 'public', 'themescovs58', 'nonorientationprocov58', 'TYPE_ETAPECOV' );
-- SELECT add_missing_table_field ( 'public', 'themescovs58', 'regressionorientationcov58', 'TYPE_ETAPECOV' );

--==============================================================================

DROP TABLE IF EXISTS nonorientationsproscovs58 CASCADE;
CREATE TABLE nonorientationsproscovs58 (
    id					SERIAL NOT NULL PRIMARY KEY,
	dossiercov58_id		INTEGER NOT NULL REFERENCES dossierscovs58(id) ON DELETE CASCADE ON UPDATE CASCADE,
	orientstruct_id		INTEGER NOT NULL REFERENCES orientsstructs(id) ON DELETE CASCADE ON UPDATE CASCADE,
	user_id				INTEGER DEFAULT NULL REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
	nvorientstruct_id	INTEGER DEFAULT NULL REFERENCES orientsstructs(id) ON DELETE CASCADE ON UPDATE CASCADE,
    created				TIMESTAMP WITHOUT TIME ZONE,
    modified			TIMESTAMP WITHOUT TIME ZONE
);

CREATE UNIQUE INDEX nonorientationsproscovs58_dossiercov58_id_idx ON nonorientationsproscovs58(dossiercov58_id);
CREATE INDEX nonorientationsproscovs58_orientstruct_id_idx ON nonorientationsproscovs58(orientstruct_id);
CREATE INDEX nonorientationsproscovs58_user_id_idx ON nonorientationsproscovs58(user_id);
CREATE INDEX nonorientationsproscovs58_nvorientstruct_id_idx ON nonorientationsproscovs58(nvorientstruct_id);

--------------------------------------------------------------------------------

DROP TABLE IF EXISTS decisionsnonorientationsproscovs58;
CREATE TABLE decisionsnonorientationsproscovs58 (
	id						SERIAL NOT NULL PRIMARY KEY,
	passagecov58_id			INTEGER NOT NULL REFERENCES passagescovs58(id) ON DELETE CASCADE ON UPDATE CASCADE,
	etapecov				VARCHAR(10) NOT NULL,
	decisioncov				VARCHAR(15) NOT NULL,
	typeorient_id			INTEGER DEFAULT NULL REFERENCES typesorients(id) ON DELETE CASCADE ON UPDATE CASCADE,
	structurereferente_id	INTEGER DEFAULT NULL REFERENCES structuresreferentes(id) ON DELETE CASCADE ON UPDATE CASCADE,
	referent_id				INTEGER DEFAULT NULL REFERENCES referents(id) ON DELETE CASCADE ON UPDATE CASCADE,
	datevalidation			DATE,
	commentaire				TEXT DEFAULT NULL,
	created					TIMESTAMP WITHOUT TIME ZONE,
	modified				TIMESTAMP WITHOUT TIME ZONE
);

ALTER TABLE decisionsnonorientationsproscovs58 ADD CONSTRAINT decisionsnonorientationsproscovs58_etapecov_in_list_chk CHECK ( cakephp_validate_in_list( etapecov, ARRAY['cree','traitement','ajourne','finalise'] ) );
ALTER TABLE decisionsnonorientationsproscovs58 ADD CONSTRAINT decisionsnonorientationsproscovs58_decisioncov_in_list_chk CHECK ( cakephp_validate_in_list( decisioncov, ARRAY['reorientation','maintienref','annule','reporte'] ) );

CREATE INDEX decisionsnonorientationsproscovs58_passagecov58_id_idx ON decisionsnonorientationsproscovs58( passagecov58_id );
CREATE INDEX decisionsnonorientationsproscovs58_etapecov_idx ON decisionsnonorientationsproscovs58( etapecov );
CREATE INDEX decisionsnonorientationsproscovs58_decisioncov_idx ON decisionsnonorientationsproscovs58( decisioncov );
CREATE UNIQUE INDEX decisionsnonorientationsproscovs58_passagecov58_id_etapecov_idx ON decisionsnonorientationsproscovs58(passagecov58_id, etapecov);
-- CREATE INDEX decisionsnonorientationsproscovs58_typeorient_id_idx ON decisionsnonorientationsproscovs58(typeorient_id);
-- CREATE INDEX decisionsnonorientationsproscovs58_structurereferente_id_idx ON decisionsnonorientationsproscovs58(structurereferente_id);
-- CREATE INDEX decisionsnonorientationsproscovs58_referent_id_idx ON decisionsnonorientationsproscovs58(referent_id);

--==============================================================================
--
-- DROP TABLE IF EXISTS proposnonorientationsproscovs58;
-- CREATE TABLE proposnonorientationsproscovs58 (
-- 	id 							SERIAL NOT NULL PRIMARY KEY,
-- 	dossiercov58_id				INTEGER NOT NULL REFERENCES dossierscovs58(id) ON DELETE CASCADE ON UPDATE CASCADE,
-- 	typeorient_id 				INTEGER NOT NULL REFERENCES typesorients(id) ON DELETE CASCADE ON UPDATE CASCADE,
-- 	orientstruct_id 			INTEGER NOT NULL REFERENCES orientsstructs(id) ON DELETE CASCADE ON UPDATE CASCADE,
-- 	structurereferente_id 		INTEGER NOT NULL REFERENCES structuresreferentes(id) ON DELETE CASCADE ON UPDATE CASCADE,
-- 	referent_id 				INTEGER DEFAULT NULL REFERENCES referents(id) ON DELETE CASCADE ON UPDATE CASCADE,
-- 	datedemande 				DATE NOT NULL,
-- 	rgorient 					INTEGER NOT NULL,
-- 	covtypeorient_id 			INTEGER DEFAULT NULL REFERENCES typesorients(id) ON DELETE CASCADE ON UPDATE CASCADE,
-- 	covstructurereferente_id	INTEGER DEFAULT NULL REFERENCES structuresreferentes(id) ON DELETE CASCADE ON UPDATE CASCADE,
-- 	datevalidation				DATE DEFAULT NULL,
-- 	commentaire 				TEXT DEFAULT NULL
-- );
-- COMMENT ON TABLE proposnonorientationsproscovs58 IS 'Demandes de maintien en social en attente de validation par la COV (cg58)';
--
-- CREATE INDEX proposnonorientationsproscovs58_dossiercov58_id_idx ON proposnonorientationsproscovs58(dossiercov58_id);
-- CREATE INDEX proposnonorientationsproscovs58_typeorient_id_idx ON proposnonorientationsproscovs58(typeorient_id);
-- CREATE INDEX proposnonorientationsproscovs58_orientstruct_id_idx ON proposnonorientationsproscovs58(orientstruct_id);
-- CREATE INDEX proposnonorientationsproscovs58_structurereferente_id_idx ON proposnonorientationsproscovs58(structurereferente_id);
-- CREATE INDEX proposnonorientationsproscovs58_referent_id_idx ON proposnonorientationsproscovs58(referent_id);
-- CREATE INDEX proposnonorientationsproscovs58_covtypeorient_id_idx ON proposnonorientationsproscovs58(covtypeorient_id);
-- CREATE INDEX proposnonorientationsproscovs58_covstructurereferente_id_idx ON proposnonorientationsproscovs58(covstructurereferente_id);


-- *****************************************************************************
-- Version
-- *****************************************************************************

DROP TABLE IF EXISTS version;
CREATE TABLE version
(
	webrsa VARCHAR(255)
);
INSERT INTO version(webrsa) VALUES ('2.9.0');

--------------------------------------------------------------------------------
-- 20150407: CG 66, ajout d'une valeur d'enum pour les decisions EP
--------------------------------------------------------------------------------

SELECT alter_enumtype('TYPE_DECISIONDEFAUTINSERTIONEP66', ARRAY['suspensionnonrespect', 'suspensiondefaut', 'suspensionsanction', 'maintien', 'maintienorientsoc', 'reorientationprofverssoc', 'reorientationsocversprof', 'annule', 'reporte']);


-- *****************************************************************************
COMMIT;
-- *****************************************************************************
