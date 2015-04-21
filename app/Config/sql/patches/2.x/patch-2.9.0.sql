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

SELECT public.update_duree_engag_integer( 'bilansparcours66' );
SELECT public.update_duree_engag_integer( 'contratsinsertion' );
SELECT public.update_duree_engag_integer( 'proposcontratsinsertioncovs58' );
SELECT public.update_duree_engag_integer( 'decisionsproposcontratsinsertioncovs58' );

DROP FUNCTION public.update_duree_engag_integer( p_table TEXT );

--------------------------------------------------------------------------------
-- 20150403: Création de deux nouvelles thématiques de COV pour le CG 58:
--           nonorientationsproscovs58 et regressionsorientationscovs58.
-- -> decisionsnonorientationsproscovs58, decisionsregressionsorientationscovs58
--------------------------------------------------------------------------------

SELECT alter_enumtype( 'TYPE_THEMECOV58', ARRAY['proposorientationscovs58','proposcontratsinsertioncovs58','proposnonorientationsproscovs58','proposorientssocialescovs58','nonorientationsproscovs58','regressionsorientationscovs58']);

INSERT INTO themescovs58 ( name ) VALUES
	( 'nonorientationsproscovs58' ),
	( 'regressionsorientationscovs58' );

SELECT add_missing_table_field ( 'public', 'themescovs58', 'nonorientationprocov58', 'TYPE_ETAPECOV' );
SELECT add_missing_table_field ( 'public', 'themescovs58', 'regressionorientationcov58', 'TYPE_ETAPECOV' );

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

--==============================================================================

DROP TABLE IF EXISTS regressionsorientationscovs58 CASCADE;
CREATE TABLE regressionsorientationscovs58 (
    id						SERIAL NOT NULL PRIMARY KEY,
	dossiercov58_id			INTEGER NOT NULL REFERENCES dossierscovs58(id) ON DELETE CASCADE ON UPDATE CASCADE,
	orientstruct_id			INTEGER NOT NULL REFERENCES orientsstructs(id) ON DELETE CASCADE ON UPDATE CASCADE,
	typeorient_id			INTEGER NOT NULL REFERENCES typesorients(id) ON DELETE CASCADE ON UPDATE CASCADE,
	structurereferente_id	INTEGER NOT NULL REFERENCES structuresreferentes(id) ON DELETE CASCADE ON UPDATE CASCADE,
	referent_id				INTEGER DEFAULT NULL REFERENCES referents(id) ON DELETE CASCADE ON UPDATE CASCADE,
	datedemande				DATE NOT NULL,
	user_id					INTEGER DEFAULT NULL REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
	nvorientstruct_id		INTEGER DEFAULT NULL REFERENCES orientsstructs(id) ON DELETE CASCADE ON UPDATE CASCADE,
    created					TIMESTAMP WITHOUT TIME ZONE,
    modified				TIMESTAMP WITHOUT TIME ZONE
);

CREATE UNIQUE INDEX regressionsorientationscovs58_dossiercov58_id_idx ON regressionsorientationscovs58(dossiercov58_id);
CREATE INDEX regressionsorientationscovs58_orientstruct_id_idx ON regressionsorientationscovs58(orientstruct_id);
CREATE INDEX regressionsorientationscovs58_typeorient_id_idx ON regressionsorientationscovs58(typeorient_id);
CREATE INDEX regressionsorientationscovs58_structurereferente_id_idx ON regressionsorientationscovs58(structurereferente_id);
CREATE INDEX regressionsorientationscovs58_referent_id_idx ON regressionsorientationscovs58(referent_id);
CREATE INDEX regressionsorientationscovs58_user_id_idx ON regressionsorientationscovs58(user_id);
CREATE INDEX regressionsorientationscovs58_nvorientstruct_id_idx ON regressionsorientationscovs58(nvorientstruct_id);

--------------------------------------------------------------------------------

DROP TABLE IF EXISTS decisionsregressionsorientationscovs58;
CREATE TABLE decisionsregressionsorientationscovs58 (
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

ALTER TABLE decisionsregressionsorientationscovs58 ADD CONSTRAINT decisionsregressionsorientationscovs58_etapecov_in_list_chk CHECK ( cakephp_validate_in_list( etapecov, ARRAY['cree','traitement','ajourne','finalise'] ) );
ALTER TABLE decisionsregressionsorientationscovs58 ADD CONSTRAINT decisionsregressionsorientationscovs58_decisioncov_in_list_chk CHECK ( cakephp_validate_in_list( decisioncov, ARRAY['accepte','refuse','annule','reporte'] ) );

CREATE INDEX decisionsregressionsorientationscovs58_passagecov58_id_idx ON decisionsregressionsorientationscovs58( passagecov58_id );
CREATE INDEX decisionsregressionsorientationscovs58_etapecov_idx ON decisionsregressionsorientationscovs58( etapecov );
CREATE INDEX decisionsregressionsorientationscovs58_decisioncov_idx ON decisionsregressionsorientationscovs58( decisioncov );
CREATE UNIQUE INDEX decisionsregressionsorientationscovs58_passagecov58_id_etapecov_idx ON decisionsregressionsorientationscovs58(passagecov58_id, etapecov);

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

--------------------------------------------------------------------------------
-- 20150420: ajout de la règle de validation "comparison"
--------------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_comparison( p_check1 float,p_operator text,p_check2 float ) RETURNS boolean AS
$$
	BEGIN
		RETURN p_check1 IS NULL
			OR(
				p_operator IS NOT NULL
				AND p_check2 IS NOT NULL
				AND (
					( p_operator IN ( '>', 'is greater' ) AND p_check1 > p_check2 )
					OR ( p_operator IN ( '>=', 'greater or equal' ) AND p_check1 >= p_check2 )
					OR ( p_operator IN ( '==', 'equal to' ) AND p_check1 = p_check2 )
					OR ( p_operator IN ( '!=', 'not equal' ) AND p_check1 <> p_check2 )
					OR ( p_operator IN ( '<', 'is less' ) AND p_check1 < p_check2 )
					OR ( p_operator IN ( '<=', 'less or equal' ) AND p_check1 <= p_check2 )
				)
			);
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_comparison( p_check1 float,p_operator text,p_check2 float ) IS
	'@see http://api.cakephp.org/2.2/class-Validation.html#_comparison';

--------------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_compare_dates( p_check1 TIMESTAMP, p_check2 TIMESTAMP, p_comparator text ) RETURNS boolean AS
$$
	BEGIN
		RETURN ( p_check1 IS NULL OR p_check2 IS NULL )
			OR(
				p_comparator IS NOT NULL
				AND p_check2 IS NOT NULL
				AND (
					( p_comparator IN ( '>', 'is greater' ) AND p_check1 > p_check2 )
					OR ( p_comparator IN ( '>=', 'greater or equal' ) AND p_check1 >= p_check2 )
					OR ( p_comparator IN ( '==', 'equal to' ) AND p_check1 = p_check2 )
					OR ( p_comparator IN ( '<', 'is less' ) AND p_check1 < p_check2 )
					OR ( p_comparator IN ( '<=', 'less or equal' ) AND p_check1 <= p_check2 )
				)
			);
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_compare_dates( p_check1 TIMESTAMP, p_check2 TIMESTAMP, p_comparator text ) IS
	'@see Validation2.Validation2RulesComparisonBehavior::compareDates()';

--------------------------------------------------------------------------------
-- 20150420: CG 58 (et autres CG); la durée du CER doit être un nombre entier
-- positif et la date de fin doit être strictement supérieure à la date de début
--------------------------------------------------------------------------------

ALTER TABLE proposcontratsinsertioncovs58 ADD CONSTRAINT proposcontratsinsertioncovs58_duree_engag_comparison_chk CHECK ( cakephp_validate_comparison( duree_engag, '>', 0 ) );
ALTER TABLE proposcontratsinsertioncovs58 ADD CONSTRAINT proposcontratsinsertioncovs58_dd_ci_compare_dates_chk CHECK ( cakephp_validate_compare_dates( dd_ci, df_ci, '<' ) );
ALTER TABLE proposcontratsinsertioncovs58 ADD CONSTRAINT proposcontratsinsertioncovs58_df_ci_compare_dates_chk CHECK ( cakephp_validate_compare_dates( df_ci, dd_ci, '>' ) );

ALTER TABLE decisionsproposcontratsinsertioncovs58 ADD CONSTRAINT decisionsproposcontratsinsertioncovs58_duree_engag_comparison_chk CHECK ( cakephp_validate_comparison( duree_engag, '>', 0 ) );
ALTER TABLE decisionsproposcontratsinsertioncovs58 ADD CONSTRAINT decisionsproposcontratsinsertioncovs58_dd_ci_compare_dates_chk CHECK ( cakephp_validate_compare_dates( dd_ci, df_ci, '<' ) );
ALTER TABLE decisionsproposcontratsinsertioncovs58 ADD CONSTRAINT decisionsproposcontratsinsertioncovs58_df_ci_compare_dates_chk CHECK ( cakephp_validate_compare_dates( df_ci, dd_ci, '>' ) );

/*
FIXME: SELECT * FROM contratsinsertion WHERE dd_ci > df_ci;
CG 58
	-> 1@cg58_20150402_orig
CG 66
	-> 1@cg66_20140923_orig
	-> 1@cg66_20150318_orig
CG 93
	-> 9@cg93_20150211_orig
CG 976
	-> 0@cg976_20141127_orig
	-> 0@cg976_20141215_orig
*/
-- ALTER TABLE contratsinsertion ADD CONSTRAINT contratsinsertion_duree_engag_comparison_chk CHECK ( cakephp_validate_comparison( duree_engag, '>', 0 ) );
-- ALTER TABLE contratsinsertion ADD CONSTRAINT contratsinsertion_dd_ci_compare_dates_chk CHECK ( cakephp_validate_compare_dates( dd_ci, df_ci, '<' ) );
-- ALTER TABLE contratsinsertion ADD CONSTRAINT contratsinsertion_df_ci_compare_dates_chk CHECK ( cakephp_validate_compare_dates( df_ci, dd_ci, '>' ) );

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
