SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
-- SET client_min_messages = notice;
SET escape_string_warning = off;
SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

-- *****************************************************************************
BEGIN;
-- *****************************************************************************

CREATE OR REPLACE FUNCTION public.enumtype_to_validate_in_list( p_schema TEXT, p_table TEXT, p_field TEXT ) RETURNS VOID AS
$$
	DECLARE
		v_row_field RECORD;
		v_select_query TEXT;
		v_count_query TEXT;
		v_row_count RECORD;
		v_enum_query TEXT;
		v_enum_row RECORD;
		v_enum_value TEXT;
		v_enum_i INTEGER;

		v_altercolumn_query TEXT;
		v_droptype_query TEXT;
		v_inlist_query TEXT;
		v_inlist_maxlength INTEGER;
		v_columndefault_query TEXT;
	BEGIN
		v_select_query := 'SELECT isc.table_schema, isc.table_name, isc.column_name, isc.udt_name, isc.column_default
			FROM information_schema.columns AS isc
			WHERE
				isc.data_type = ''USER-DEFINED''
				AND isc.udt_name IN (
					SELECT isc2.udt_name
						FROM information_schema.columns AS isc2
						WHERE
							isc2.data_type = ''USER-DEFINED''
							AND isc2.table_schema = ''' || p_schema || '''
							AND isc2.table_name = ''' || p_table || '''
							AND isc2.column_name = ''' || p_field || '''
			)';

		v_count_query := 'SELECT COUNT(tables.*) AS count FROM ( ' || v_select_query || ' ) AS tables';
		EXECUTE v_count_query INTO v_row_count;

		-- FIXME: pas d'erreur en-dehors des tests
		IF v_row_count.count = 0 THEN
			RAISE EXCEPTION 'Le champ %.%.% n''est pas de type ENUM', p_schema, p_table, p_field;
		END IF;

		-- Transformation de la requête pour ne concerner que le champ demandé
		v_select_query := v_select_query
			|| 'AND isc.table_schema = ''' || p_schema || '''
			AND isc.table_name = ''' || p_table || '''
			AND isc.column_name = ''' || p_field || ''';';

		FOR v_row_field IN EXECUTE v_select_query
		LOOP
			-- 1°) Récupération des valeurs
			v_enum_query := 'SELECT enum_range( null::' || v_row_field.udt_name || ' )::TEXT[] AS enum;';
			EXECUTE v_enum_query INTO v_enum_row;

			-- Recherche de la longueur maximale du champ
			v_inlist_maxlength := 0;
			FOR v_enum_value IN SELECT unnest( v_enum_row.enum )
			LOOP
				IF LENGTH( v_enum_value ) > v_inlist_maxlength THEN
					v_inlist_maxlength := LENGTH( v_enum_value );
				END IF;
			END LOOP;

			-- 2°) Transformation de la colonne
 			v_altercolumn_query := 'ALTER TABLE ' || p_schema || '.' || p_table || ' ALTER COLUMN ' || p_field || ' TYPE VARCHAR(' || v_inlist_maxlength || ') USING CAST(' || p_field || ' AS VARCHAR(' || v_inlist_maxlength || '));';
			EXECUTE v_altercolumn_query;

			-- 3°) Ajout de la contrainte cakephp_calidate_in_list()
			v_inlist_query := 'ALTER TABLE ' || p_schema || '.' || p_table || ' ADD CONSTRAINT ' || p_table || '_' || p_field || '_in_list_chk CHECK ( cakephp_validate_in_list( ' || p_field || ', ARRAY[\'' || ARRAY_TO_STRING( v_enum_row.enum, '\', \'' ) || '\'] ) );';
			EXECUTE v_inlist_query;

			-- 4°) Changement du type de la valeur par défaut
			IF v_row_field.column_default ~ '::[^'']+$' THEN
				v_columndefault_query := 'ALTER TABLE ' || p_schema || '.' || p_table || ' ALTER COLUMN ' || p_field || ' SET DEFAULT ' || REGEXP_REPLACE( v_row_field.column_default, '::[^'']+$', '' ) || ';';
				EXECUTE v_columndefault_query;
			END IF;

			-- 5°) Suppression de l'ENUM si on est le seul à l'utiliser
			IF v_row_count.count = 1 THEN
				v_droptype_query := 'DROP TYPE IF EXISTS ' || v_row_field.udt_name || ';';
				EXECUTE v_droptype_query;
			END IF;
		END LOOP;
	END;
$$
LANGUAGE plpgsql VOLATILE;

COMMENT ON FUNCTION public.enumtype_to_validate_in_list( p_schema TEXT, p_table TEXT, p_field TEXT ) IS
	'Permet de transformer un champ de type ENUM en un champ de type VARCHAR, avec ajout d''une contrainte cakephp_validate_in_list() et la suppression du type s''il n''est plus utilisé.';

--------------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION public.table_enumtypes_to_validate_in_list( p_schema TEXT, p_table TEXT ) RETURNS VOID AS
$$
	DECLARE
		v_row_field RECORD;
		v_select_query TEXT;
		v_function_query TEXT;
	BEGIN
		v_select_query := 'SELECT isc.table_schema, isc.table_name, isc.column_name
			FROM information_schema.columns AS isc
			WHERE
				isc.data_type = ''USER-DEFINED''
				AND isc.table_schema = ''' || p_schema || '''
				AND isc.table_name = ''' || p_table || ''';';

		FOR v_row_field IN EXECUTE v_select_query
		LOOP
			EXECUTE public.enumtype_to_validate_in_list( v_row_field.table_schema, v_row_field.table_name, v_row_field.column_name );
		END LOOP;
	END;
$$
LANGUAGE plpgsql VOLATILE;

COMMENT ON FUNCTION public.table_enumtypes_to_validate_in_list( p_schema TEXT, p_table TEXT ) IS
	'Permet de transformer tous les champs de type ENUM d''une table en champs de type VARCHAR, avec ajout d''une contrainte cakephp_validate_in_list() et la suppression du type s''il n''est plus utilisé.';

--------------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION public.table_defaultvalues_enumtypes_to_varchar( p_schema TEXT, p_table TEXT ) RETURNS VOID AS
$$
	DECLARE
		v_row_field RECORD;
		v_select_query TEXT;
		v_columndefault_query TEXT;
	BEGIN
		v_select_query := 'SELECT isc.table_schema, isc.table_name, isc.column_name, isc.udt_name, isc.column_default
			FROM information_schema.columns AS isc
			WHERE
				isc.data_type <> ''USER-DEFINED''
				AND isc.table_schema = ''' || p_schema || '''
				AND isc.table_name = ''' || p_table || '''
				AND isc.column_default ~ ''::type_[^'''']+$''
			;';

		FOR v_row_field IN EXECUTE v_select_query
		LOOP
			v_columndefault_query := 'ALTER TABLE ' || v_row_field.table_schema || '.' || v_row_field.table_name || ' ALTER COLUMN ' || v_row_field.column_name || ' SET DEFAULT ' || REGEXP_REPLACE( v_row_field.column_default, '::[^'']+$', '' ) || ';';
			EXECUTE v_columndefault_query;
		END LOOP;
	END;
$$
LANGUAGE plpgsql VOLATILE;

COMMENT ON FUNCTION public.table_defaultvalues_enumtypes_to_varchar( p_schema TEXT, p_table TEXT ) IS
	'Permet de transformer toutes les valeurs par défaut de type ENUM des champs de type non ENUM d''une table.';

--------------------------------------------------------------------------------

SELECT public.table_enumtypes_to_validate_in_list( 'public', 'cuis' );
SELECT public.table_defaultvalues_enumtypes_to_varchar( 'public', 'cuis' );

--------------------------------------------------------------------------------
-- 20130121: ajout des valeurs 'annule' et 'reporte' à la décision de la thématique
-- d'EP nonorientationsproseps66.
--------------------------------------------------------------------------------

SELECT public.alter_enumtype( 'TYPE_DECISIONNONORIENTATIONPROEP66', ARRAY['reorientation','maintienref','annule','reporte'] );

--------------------------------------------------------------------------------
-- 20140121: Création des nouvelles tables intégrant les nouveaux Codes ROME
--------------------------------------------------------------------------------
DROP TABLE IF EXISTS codesfamillesromev3 CASCADE;
CREATE TABLE codesfamillesromev3 (
    id          SERIAL NOT NULL PRIMARY KEY,
    code        VARCHAR(1) NOT NULL,
    name        VARCHAR(150) NOT NULL,
    created     TIMESTAMP WITHOUT TIME ZONE,
    modified    TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE codesfamillesromev3 IS 'Codes ROME V3 - Codes familles';

DROP INDEX IF EXISTS codesfamillesromev3_name_idx;
CREATE INDEX codesfamillesromev3_name_idx ON codesfamillesromev3( name );
DROP INDEX IF EXISTS codesfamillesromev3_code_idx;
CREATE INDEX codesfamillesromev3_code_idx ON codesfamillesromev3( code );

DROP TABLE IF EXISTS codesdomainesprosromev3 CASCADE;
CREATE TABLE codesdomainesprosromev3 (
    id                          SERIAL NOT NULL PRIMARY KEY,
    codefamilleromev3_id          INTEGER NOT NULL REFERENCES codesfamillesromev3(id) ON DELETE CASCADE ON UPDATE CASCADE,
    code                        VARCHAR(2) NOT NULL,
    name                        VARCHAR(150) NOT NULL,
    created                     TIMESTAMP WITHOUT TIME ZONE,
    modified                    TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE codesdomainesprosromev3 IS 'Codes ROME V3 - Domaines professionnels';

DROP INDEX IF EXISTS codesdomainesprosromev3_name_idx;
CREATE INDEX codesdomainesprosromev3_name_idx ON codesdomainesprosromev3( name );
DROP INDEX IF EXISTS codesdomainesprosromev3_code_idx;
CREATE INDEX codesdomainesprosromev3_code_idx ON codesdomainesprosromev3( code );
DROP INDEX IF EXISTS codesdomainesprosromev3_codefamilleromev3_id_idx;
CREATE INDEX codesdomainesprosromev3_codefamilleromev3_id_idx ON codesdomainesprosromev3( codefamilleromev3_id );

DROP TABLE IF EXISTS codesmetiersromev3 CASCADE;
CREATE TABLE codesmetiersromev3 (
    id                              SERIAL NOT NULL PRIMARY KEY,
    codedomaineproromev3_id          INTEGER NOT NULL REFERENCES codesdomainesprosromev3(id) ON DELETE CASCADE ON UPDATE CASCADE,
    code                            VARCHAR(2) NOT NULL,
    name                            VARCHAR(150) NOT NULL,
    created                         TIMESTAMP WITHOUT TIME ZONE,
    modified                        TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE codesmetiersromev3 IS 'Codes ROME V3 - Codes métiers';

DROP INDEX IF EXISTS codesmetiersromev3_name_idx;
CREATE INDEX codesmetiersromev3_name_idx ON codesmetiersromev3( name );
DROP INDEX IF EXISTS codesmetiersromev3_code_idx;
CREATE INDEX codesmetiersromev3_code_idx ON codesmetiersromev3( code );
DROP INDEX IF EXISTS codesmetiersromev3_codedomaineprorome_id_idx;
CREATE INDEX codesmetiersromev3_codedomaineproromev3_id_idx ON codesmetiersromev3( codedomaineproromev3_id );

DROP TABLE IF EXISTS codesappellationsromev3 CASCADE;
CREATE TABLE codesappellationsromev3 (
    id                              SERIAL NOT NULL PRIMARY KEY,
    codemetierromev3_id               INTEGER NOT NULL REFERENCES codesmetiersromev3(id) ON DELETE CASCADE ON UPDATE CASCADE,
    name                            VARCHAR(150) NOT NULL,
    created                         TIMESTAMP WITHOUT TIME ZONE,
    modified                        TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE codesappellationsromev3 IS 'Codes ROME V3 - Codes appellations métiers';

DROP INDEX IF EXISTS codesappellationsromev3_name_idx;
CREATE INDEX codesappellationsromev3_name_idx ON codesappellationsromev3( name );
DROP INDEX IF EXISTS codesappellationsromev3_codemetierrome_id_idx;
CREATE INDEX codesappellationsromev3_codemetierromev3_id_idx ON codesappellationsromev3( codemetierromev3_id );


ALTER TABLE decisionsdossierspcgs66 ALTER COLUMN infotransmise TYPE TEXT;

-- -----------------------------------------------------------------------------
-- Règlde validation CakePHP alphaNumeric ( string|array $check )
-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_alpha_numeric( p_check text ) RETURNS boolean AS
$$
	BEGIN
		RETURN p_check IS NULL OR ( p_check ~ E'^[^[:punct:]|[:blank:]|[:space:]|[:cntrl:]]+$'  );
	END;
$$
LANGUAGE plpgsql IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_alpha_numeric( p_check text ) IS
	'@see http://api.cakephp.org/2.2/class-Validation.html#_alphaNumeric';

-- *****************************************************************************
-- 20140307: ajout du fax pour la structure référente
-- *****************************************************************************
SELECT add_missing_table_field( 'public', 'structuresreferentes', 'numfax', 'VARCHAR(20)' );
ALTER TABLE structuresreferentes ALTER COLUMN numfax SET DEFAULT NULL;

-- *****************************************************************************
-- Fiche de prescription - CG 93
-- lib/Cake/Console/cake Graphviz.GraphvizMpd -t "/(^personnes$|^referents$|^fichesprescriptions93$|fps93$|^situationsallocataires$|^structuresreferentes$)/" && dot -K fdp -T png -o ./graphviz_mpd.png ./graphviz_mpd.dot && gwenview ./graphviz_mpd.png > /dev/null 2>&1
-- *****************************************************************************

DROP TABLE IF EXISTS thematiquesfps93 CASCADE;
CREATE TABLE thematiquesfps93 (
    id			SERIAL NOT NULL PRIMARY KEY,
	type		VARCHAR(10) NOT NULL,
    name		VARCHAR(250) NOT NULL,
    created     TIMESTAMP WITHOUT TIME ZONE,
    modified    TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE thematiquesfps93 IS 'Thématiques pour la fiche de prescription - CG 93';

CREATE UNIQUE INDEX thematiquesfps93_type_name_idx ON thematiquesfps93( type, name );

ALTER TABLE thematiquesfps93 ADD CONSTRAINT thematiquesfps93_type_in_list_chk CHECK ( cakephp_validate_in_list( type, ARRAY['pdi','horspdi'] ) );

--------------------------------------------------------------------------------

DROP TABLE IF EXISTS categoriesfps93 CASCADE;
CREATE TABLE categoriesfps93 (
    id					SERIAL NOT NULL PRIMARY KEY,
	thematiquefp93_id	INTEGER NOT NULL REFERENCES thematiquesfps93(id),
    name				VARCHAR(250) NOT NULL,
    created				TIMESTAMP WITHOUT TIME ZONE,
    modified			TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE categoriesfps93 IS 'Catégories pour la fiche de prescription - CG 93';

CREATE UNIQUE INDEX categoriesfps93_thematiquefp93_id_name_idx ON categoriesfps93( thematiquefp93_id, name );

--------------------------------------------------------------------------------

DROP TABLE IF EXISTS filieresfps93 CASCADE;
CREATE TABLE filieresfps93 (
    id					SERIAL NOT NULL PRIMARY KEY,
	categoriefp93_id	INTEGER NOT NULL REFERENCES categoriesfps93(id),
    name				VARCHAR(250) NOT NULL,
    created				TIMESTAMP WITHOUT TIME ZONE,
    modified			TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE filieresfps93 IS 'Filières pour la fiche de prescription - CG 93';

CREATE UNIQUE INDEX filieresfps93_categoriefp93_id_name_idx ON filieresfps93( categoriefp93_id, name );

--------------------------------------------------------------------------------

DROP TABLE IF EXISTS prestatairesfps93 CASCADE;
CREATE TABLE prestatairesfps93 (
    id					SERIAL NOT NULL PRIMARY KEY,
    name				VARCHAR(250) NOT NULL,
	-- TODO: mettre le reste des champs
    created				TIMESTAMP WITHOUT TIME ZONE,
    modified			TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE prestatairesfps93 IS 'Prestataires pour la fiche de prescription - CG 93';

CREATE UNIQUE INDEX prestatairesfps93_name_idx ON prestatairesfps93( name );

--------------------------------------------------------------------------------

DROP TABLE IF EXISTS adressesprestatairesfps93 CASCADE;
CREATE TABLE adressesprestatairesfps93 (
    id					SERIAL NOT NULL PRIMARY KEY,
	prestatairefp93_id	INTEGER NOT NULL REFERENCES prestatairesfps93( id ) ON DELETE CASCADE ON UPDATE CASCADE,
	adresse				TEXT NOT NULL,
	codepos				VARCHAR(5) NOT NULL,
	localite			VARCHAR(250) NOT NULL,
	tel					VARCHAR(10) DEFAULT NULL,
	fax					VARCHAR(10) DEFAULT NULL,
    created				TIMESTAMP WITHOUT TIME ZONE,
    modified			TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE adressesprestatairesfps93 IS 'Adresses des prestataires pour la fiche de prescription - CG 93';

CREATE INDEX adressesprestatairesfps93_prestatairefp93_id_idx ON adressesprestatairesfps93( prestatairefp93_id );

--------------------------------------------------------------------------------

DROP TABLE IF EXISTS actionsfps93 CASCADE;
CREATE TABLE actionsfps93 (
    id					SERIAL NOT NULL PRIMARY KEY,
	filierefp93_id		INTEGER NOT NULL REFERENCES filieresfps93(id),
	prestatairefp93_id	INTEGER NOT NULL REFERENCES prestatairesfps93(id),
    name				VARCHAR(250) NOT NULL,
    numconvention		VARCHAR(250) DEFAULT NULL,
	annee				INTEGER NOT NULL,
	actif				CHAR(1) NOT NULL,
    created				TIMESTAMP WITHOUT TIME ZONE,
    modified			TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE actionsfps93 IS 'Actions pour la fiche de prescription - CG 93';

CREATE INDEX actionsfps93_filierefp93_id_idx ON actionsfps93( filierefp93_id );
CREATE INDEX actionsfps93_prestatairefp93_id_idx ON actionsfps93( prestatairefp93_id );
CREATE INDEX actionsfps93_upper_numconvention_idx ON actionsfps93( UPPER( numconvention ) );
CREATE UNIQUE INDEX actionsfps93_filierefp93_id_prestatairefp93_id_name_annee_actif_idx ON actionsfps93( filierefp93_id, prestatairefp93_id, name, annee ) WHERE actif = '1';

ALTER TABLE actionsfps93 ADD CONSTRAINT actionsfps93_actif_in_list_chk CHECK ( cakephp_validate_in_list( actif, ARRAY['0','1'] ) );
ALTER TABLE actionsfps93 ADD CONSTRAINT actionsfps93_numconvention_alpha_numeric_chk CHECK ( cakephp_validate_alpha_numeric( numconvention ) );

--------------------------------------------------------------------------------

DROP TABLE IF EXISTS motifsnonreceptionsfps93 CASCADE;
CREATE TABLE motifsnonreceptionsfps93 (
    id			SERIAL NOT NULL PRIMARY KEY,
    name		VARCHAR(250) NOT NULL,
    autre		VARCHAR(1) NOT NULL DEFAULT '0',
    created     TIMESTAMP WITHOUT TIME ZONE,
    modified    TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE motifsnonreceptionsfps93 IS 'Motifs de non réception pour la fiche de prescription - CG 93';

CREATE UNIQUE INDEX motifsnonreceptionsfps93_name_idx ON motifsnonreceptionsfps93( name );

ALTER TABLE motifsnonreceptionsfps93 ADD CONSTRAINT motifsnonreceptionsfps93_autre_in_list_chk CHECK ( cakephp_validate_in_list( autre, ARRAY['0','1'] ) );

--------------------------------------------------------------------------------

DROP TABLE IF EXISTS motifsnonretenuesfps93 CASCADE;
CREATE TABLE motifsnonretenuesfps93 (
    id			SERIAL NOT NULL PRIMARY KEY,
    name		VARCHAR(250) NOT NULL,
    autre		VARCHAR(1) NOT NULL DEFAULT '0',
    created     TIMESTAMP WITHOUT TIME ZONE,
    modified    TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE motifsnonretenuesfps93 IS 'Motifs de non réception pour la fiche de prescription - CG 93';

CREATE UNIQUE INDEX motifsnonretenuesfps93_name_idx ON motifsnonretenuesfps93( name );

ALTER TABLE motifsnonretenuesfps93 ADD CONSTRAINT motifsnonretenuesfps93_autre_in_list_chk CHECK ( cakephp_validate_in_list( autre, ARRAY['0','1'] ) );

--------------------------------------------------------------------------------

DROP TABLE IF EXISTS motifsnonsouhaitsfps93 CASCADE;
CREATE TABLE motifsnonsouhaitsfps93 (
    id			SERIAL NOT NULL PRIMARY KEY,
    name		VARCHAR(250) NOT NULL,
    autre		VARCHAR(1) NOT NULL DEFAULT '0',
    created     TIMESTAMP WITHOUT TIME ZONE,
    modified    TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE motifsnonsouhaitsfps93 IS 'Motifs de non souhait d\'intégration pour la fiche de prescription - CG 93';

CREATE UNIQUE INDEX motifsnonsouhaitsfps93_name_idx ON motifsnonsouhaitsfps93( name );

ALTER TABLE motifsnonsouhaitsfps93 ADD CONSTRAINT motifsnonsouhaitsfps93_autre_in_list_chk CHECK ( cakephp_validate_in_list( autre, ARRAY['0','1'] ) );

--------------------------------------------------------------------------------

DROP TABLE IF EXISTS motifsnonintegrationsfps93 CASCADE;
CREATE TABLE motifsnonintegrationsfps93 (
    id			SERIAL NOT NULL PRIMARY KEY,
    name		VARCHAR(250) NOT NULL,
    autre		VARCHAR(1) NOT NULL DEFAULT '0',
    created     TIMESTAMP WITHOUT TIME ZONE,
    modified    TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE motifsnonintegrationsfps93 IS 'Motifs de non intégration pour la fiche de prescription - CG 93';

CREATE UNIQUE INDEX motifsnonintegrationsfps93_name_idx ON motifsnonintegrationsfps93( name );

ALTER TABLE motifsnonintegrationsfps93 ADD CONSTRAINT motifsnonintegrationsfps93_autre_in_list_chk CHECK ( cakephp_validate_in_list( autre, ARRAY['0','1'] ) );

--------------------------------------------------------------------------------

DROP TABLE IF EXISTS fichesprescriptions93 CASCADE;
CREATE TABLE fichesprescriptions93 (
    id							SERIAL NOT NULL PRIMARY KEY,
    personne_id					INTEGER NOT NULL REFERENCES personnes(id) ON DELETE CASCADE ON UPDATE CASCADE,
	statut						VARCHAR(30) NOT NULL,
	-- Bloc "Prescripteur/Référent"
    referent_id					INTEGER NOT NULL REFERENCES referents(id) ON DELETE CASCADE ON UPDATE CASCADE,
    objet						TEXT DEFAULT NULL,
	-- Bloc "Prestataire/Partenaire"
	rdvprestataire_date			TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL,
	rdvprestataire_personne		TEXT DEFAULT NULL,
    actionfp93_id				INTEGER NOT NULL REFERENCES actionsfps93(id) ON DELETE CASCADE ON UPDATE CASCADE,
	-- TODO: adresseprestatairefp93_id
	dd_action					DATE DEFAULT NULL,
	df_action					DATE DEFAULT NULL,
	duree_action				INTEGER DEFAULT NULL,
	-- Bloc "Engagement"
	date_signature				DATE DEFAULT NULL,
	-- Bloc "Modalités de transmission"
	date_transmission			DATE DEFAULT NULL,
	-- Bloc "Résultat de l'effectivité de la prescription"
	date_retour					DATE DEFAULT NULL,
	benef_retour_presente		VARCHAR(6) DEFAULT NULL,
	retour_nom_partenaire		TEXT DEFAULT NULL,
	date_signature_partenaire	DATE DEFAULT NULL,
	-- Bloc "Suivi de l'action"
	personne_recue				VARCHAR(1) DEFAULT NULL,
	motifnonreceptionfp93_id	INTEGER DEFAULT NULL REFERENCES motifsnonreceptionsfps93(id) ON DELETE CASCADE ON UPDATE CASCADE,
	personne_nonrecue_autre		VARCHAR(250) DEFAULT NULL,

	personne_retenue			VARCHAR(1) DEFAULT NULL,
	motifnonretenuefp93_id		INTEGER DEFAULT NULL REFERENCES motifsnonretenuesfps93(id) ON DELETE CASCADE ON UPDATE CASCADE,
	personne_nonretenue_autre	VARCHAR(250) DEFAULT NULL,

	personne_souhaite_integrer	VARCHAR(1) DEFAULT NULL,
	motifnonsouhaitfp93_id		INTEGER DEFAULT NULL REFERENCES motifsnonsouhaitsfps93(id) ON DELETE CASCADE ON UPDATE CASCADE,
	personne_nonsouhaite_autre	VARCHAR(250) DEFAULT NULL,

	personne_a_integre			VARCHAR(1) DEFAULT NULL,
	personne_date_integration	DATE DEFAULT NULL,
	motifnonintegrationfp93_id	INTEGER DEFAULT NULL REFERENCES motifsnonintegrationsfps93(id) ON DELETE CASCADE ON UPDATE CASCADE,
	personne_nonintegre_autre	VARCHAR(250) DEFAULT NULL,

	date_bilan_mi_parcours		DATE DEFAULT NULL,
	date_bilan_final			DATE DEFAULT NULL,
    created						TIMESTAMP WITHOUT TIME ZONE,
    modified					TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE fichesprescriptions93 IS 'Fiche de prescription - CG 93';

CREATE INDEX fichesprescriptions93_personne_id_idx ON fichesprescriptions93( personne_id );
CREATE INDEX fichesprescriptions93_referent_id_idx ON fichesprescriptions93( referent_id );
CREATE INDEX fichesprescriptions93_actionfp93_id_idx ON fichesprescriptions93( actionfp93_id );

ALTER TABLE fichesprescriptions93 ADD CONSTRAINT fichesprescriptions93_statut_in_list_chk CHECK ( cakephp_validate_in_list( statut, ARRAY['01renseignee', '02signee', '03transmise_partenaire', '04effectivite_renseignee', '05suivi_renseigne'] ) );
ALTER TABLE fichesprescriptions93 ADD CONSTRAINT fichesprescriptions93_benef_retour_presente_in_list_chk CHECK ( cakephp_validate_in_list( benef_retour_presente, ARRAY['oui', 'non', 'excuse'] ) );
ALTER TABLE fichesprescriptions93 ADD CONSTRAINT fichesprescriptions93_personne_recue_in_list_chk CHECK ( cakephp_validate_in_list( personne_recue, ARRAY['0', '1'] ) );
ALTER TABLE fichesprescriptions93 ADD CONSTRAINT fichesprescriptions93_personne_retenue_in_list_chk CHECK ( cakephp_validate_in_list( personne_retenue, ARRAY['0', '1'] ) );
ALTER TABLE fichesprescriptions93 ADD CONSTRAINT fichesprescriptions93_personne_souhaite_integrer_in_list_chk CHECK ( cakephp_validate_in_list( personne_souhaite_integrer, ARRAY['0', '1'] ) );
ALTER TABLE fichesprescriptions93 ADD CONSTRAINT fichesprescriptions93_personne_a_integre_in_list_chk CHECK ( cakephp_validate_in_list( personne_a_integre, ARRAY['0', '1'] ) );

--------------------------------------------------------------------------------

DROP TABLE IF EXISTS modstransmsfps93 CASCADE;
CREATE TABLE modstransmsfps93 (
    id			SERIAL NOT NULL PRIMARY KEY,
    name		VARCHAR(250) NOT NULL,
    created		TIMESTAMP WITHOUT TIME ZONE,
    modified	TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE modstransmsfps93 IS 'Paramétrage des modalités de transmission pour la fiche de prescription - CG 93';

CREATE UNIQUE INDEX modstransmsfps93_name_idx ON modstransmsfps93( name );

--------------------------------------------------------------------------------

DROP TABLE IF EXISTS fichesprescriptions93_modstransmsfps93 CASCADE;
CREATE TABLE fichesprescriptions93_modstransmsfps93 (
    id						SERIAL NOT NULL PRIMARY KEY,
	ficheprescription93_id	INTEGER NOT NULL REFERENCES fichesprescriptions93( id ) ON UPDATE CASCADE ON DELETE CASCADE,
	modtransmfp93_id		INTEGER NOT NULL REFERENCES modstransmsfps93( id ) ON UPDATE CASCADE ON DELETE CASCADE,
    created		TIMESTAMP WITHOUT TIME ZONE,
    modified	TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE fichesprescriptions93_modstransmsfps93 IS 'Modalités de transmission pour la fiche de prescription - CG 93';

CREATE INDEX fichesprescriptions93_modstransmsfps93_ficheprescription93_id_idx ON fichesprescriptions93_modstransmsfps93( ficheprescription93_id );
CREATE INDEX fichesprescriptions93_modstransmsfps93_modtransmfp93_id_idx ON fichesprescriptions93_modstransmsfps93( modtransmfp93_id );
CREATE UNIQUE INDEX fichesprescriptions93_modstransmsfps93_ficheprescription93_id_modtransmfp93_id_idx ON fichesprescriptions93_modstransmsfps93( ficheprescription93_id, modtransmfp93_id );

--------------------------------------------------------------------------------

DROP TABLE IF EXISTS instantanesdonneesfps93 CASCADE;
CREATE TABLE instantanesdonneesfps93 (
    id						SERIAL NOT NULL PRIMARY KEY,
    ficheprescription93_id	INTEGER NOT NULL REFERENCES fichesprescriptions93(id) ON DELETE CASCADE ON UPDATE CASCADE,
	-- Partie "Bloc Prescripteur / Référent"
	referent_fonction		VARCHAR(30) NOT NULL,
	structure_name			VARCHAR(100) NOT NULL,
	structure_num_voie		VARCHAR(15) NOT NULL,
	structure_type_voie 	VARCHAR(6) NOT NULL,
	structure_nom_voie		VARCHAR(50) NOT NULL,
	structure_code_postal	CHAR(5) NOT NULL,
	structure_ville			VARCHAR(45) NOT NULL,
	structure_tel			VARCHAR(10) DEFAULT NULL,
	structure_fax			VARCHAR(10) DEFAULT NULL,
	referent_email			VARCHAR(78) DEFAULT NULL,
	-- Partie "Bénéficiaire"
	benef_tel_fixe			VARCHAR(14) DEFAULT NULL,
	benef_tel_port			VARCHAR(14) DEFAULT NULL,
	benef_email				VARCHAR(100) DEFAULT NULL,
	benef_nivetu			VARCHAR(4) DEFAULT NULL,
	benef_dip_ce			VARCHAR(1) DEFAULT NULL,
	benef_positioncer		VARCHAR(13) DEFAULT NULL,
	situationallocataire_id	INTEGER NOT NULL REFERENCES situationsallocataires(id) ON DELETE CASCADE ON UPDATE CASCADE,
    created					TIMESTAMP WITHOUT TIME ZONE,
    modified				TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE instantanesdonneesfps93 IS '"Instantané" de certaines données pour la fiche de prescription - CG 93';

CREATE UNIQUE INDEX instantanesdonneesfps93_ficheprescription93_id_idx ON instantanesdonneesfps93( ficheprescription93_id );
CREATE UNIQUE INDEX instantanesdonneesfps93_situationallocataire_id_idx ON instantanesdonneesfps93( situationallocataire_id );

ALTER TABLE instantanesdonneesfps93 ADD CONSTRAINT instantanesdonneesfps93_benef_nivetu_in_list_chk CHECK ( cakephp_validate_in_list( benef_nivetu, ARRAY['1201', '1202', '1203', '1204', '1205', '1206', '1207'] ) );
ALTER TABLE instantanesdonneesfps93 ADD CONSTRAINT instantanesdonneesfps93_benef_dip_ce_in_list_chk CHECK ( cakephp_validate_in_list( benef_dip_ce, ARRAY['0', '1'] ) );
ALTER TABLE instantanesdonneesfps93 ADD CONSTRAINT instantanesdonneesfps93_benef_positioncer_in_list_chk CHECK ( cakephp_validate_in_list( benef_positioncer, ARRAY['validationpdv', 'validationcg', 'valide'] ) );

--------------------------------------------------------------------------------
-- 20140221: Ajout de la date d'affectation du gestionnaire au dossier PCG (CG66)
--------------------------------------------------------------------------------
SELECT add_missing_table_field( 'public', 'dossierspcgs66', 'dateaffectation', 'DATE' );

-------------------------------------------------------------------------------------
-- 20140225 : Ajout d'un champ isactif pour masquer les infos du modules courriers des dossiers PCGs (CG66)
-------------------------------------------------------------------------------------
SELECT add_missing_table_field( 'public', 'typescourrierspcgs66', 'isactif', 'VARCHAR(1)' );
SELECT alter_table_drop_constraint_if_exists( 'public', 'typescourrierspcgs66', 'typescourrierspcgs66_isactif_in_list_chk' );
ALTER TABLE typescourrierspcgs66 ADD CONSTRAINT typescourrierspcgs66_isactif_in_list_chk CHECK ( cakephp_validate_in_list( isactif, ARRAY['0','1'] ) );
UPDATE typescourrierspcgs66 SET isactif = '1' WHERE isactif IS NULL;
ALTER TABLE typescourrierspcgs66 ALTER COLUMN isactif SET DEFAULT '1'::VARCHAR(1);
ALTER TABLE typescourrierspcgs66 ALTER COLUMN isactif SET NOT NULL;

SELECT add_missing_table_field( 'public', 'modelestypescourrierspcgs66', 'isactif', 'VARCHAR(1)' );
SELECT alter_table_drop_constraint_if_exists( 'public', 'modelestypescourrierspcgs66', 'modelestypescourrierspcgs66_isactif_in_list_chk' );
ALTER TABLE modelestypescourrierspcgs66 ADD CONSTRAINT modelestypescourrierspcgs66_isactif_in_list_chk CHECK ( cakephp_validate_in_list( isactif, ARRAY['0','1'] ) );
UPDATE modelestypescourrierspcgs66 SET isactif = '1' WHERE isactif IS NULL;
ALTER TABLE modelestypescourrierspcgs66 ALTER COLUMN isactif SET DEFAULT '1'::VARCHAR(1);
ALTER TABLE modelestypescourrierspcgs66 ALTER COLUMN isactif SET NOT NULL;

SELECT add_missing_table_field( 'public', 'piecesmodelestypescourrierspcgs66', 'isactif', 'VARCHAR(1)' );
SELECT alter_table_drop_constraint_if_exists( 'public', 'piecesmodelestypescourrierspcgs66', 'piecesmodelestypescourrierspcgs66_isactif_in_list_chk' );
ALTER TABLE piecesmodelestypescourrierspcgs66 ADD CONSTRAINT piecesmodelestypescourrierspcgs66_isactif_in_list_chk CHECK ( cakephp_validate_in_list( isactif, ARRAY['0','1'] ) );
UPDATE piecesmodelestypescourrierspcgs66 SET isactif = '1' WHERE isactif IS NULL;
ALTER TABLE piecesmodelestypescourrierspcgs66 ALTER COLUMN isactif SET DEFAULT '1'::VARCHAR(1);
ALTER TABLE piecesmodelestypescourrierspcgs66 ALTER COLUMN isactif SET NOT NULL;

-------------------------------------------------------------------------------------
-- 20140225 : Ajout d'un champ pour stocker le cumul des durées des CERs
-- respectant la requête limitecumulCER présente dans le modèle Contratinsertion.php
-------------------------------------------------------------------------------------
SELECT add_missing_table_field( 'public', 'contratsinsertion', 'cumulduree', 'INTEGER' );


-------------------------------------------------------------------------------------
-- 20140307 : Ajout d'un état supplémentaire pour les dossiers PCGs (dossier à revoir)
-------------------------------------------------------------------------------------
-- Un dossier ne possède pas d'état PCG dossierpcg66_id = 4634 en base CG66
-- UPDATE dossierspcgs66 SET etatdossierpcg = 'transmisop' WHERE id = '4634';
SELECT alter_table_drop_constraint_if_exists( 'public', 'dossierspcgs66', 'dossierspcgs66_etatdossierpcg_in_list_chk' );
ALTER TABLE dossierspcgs66 ADD CONSTRAINT dossierspcgs66_etatdossierpcg_in_list_chk CHECK ( cakephp_validate_in_list( etatdossierpcg, ARRAY['attaffect', 'attinstr', 'instrencours', 'attavistech', 'attval', 'decisionvalid', 'decisionnonvalid', 'decisionnonvalidretouravis', 'decisionvalidretouravis', 'transmisop', 'atttransmisop', 'annule', 'attinstrattpiece', 'attinstrdocarrive','arevoir'] ) );
-- *****************************************************************************
COMMIT;
-- *****************************************************************************
