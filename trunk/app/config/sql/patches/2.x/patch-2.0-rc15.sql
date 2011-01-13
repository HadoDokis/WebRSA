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

-- INFO: http://archives.postgresql.org/pgsql-sql/2005-09/msg00266.php
CREATE OR REPLACE FUNCTION public.add_missing_table_field (text, text, text, text)
RETURNS bool as '
DECLARE
  p_namespace alias for $1;
  p_table     alias for $2;
  p_field     alias for $3;
  p_type      alias for $4;
  v_row       record;
  v_query     text;
BEGIN
  select 1 into v_row from pg_namespace n, pg_class c, pg_attribute a
     where
         --public.slon_quote_brute(n.nspname) = p_namespace and
         n.nspname = p_namespace and
         c.relnamespace = n.oid and
         --public.slon_quote_brute(c.relname) = p_table and
         c.relname = p_table and
         a.attrelid = c.oid and
         --public.slon_quote_brute(a.attname) = p_field;
         a.attname = p_field;
  if not found then
    raise notice ''Upgrade table %.% - add field %'', p_namespace, p_table, p_field;
    v_query := ''alter table '' || p_namespace || ''.'' || p_table || '' add column '';
    v_query := v_query || p_field || '' '' || p_type || '';'';
    execute v_query;
    return ''t'';
  else
    return ''f'';
  end if;
END;' language plpgsql;

COMMENT ON FUNCTION public.add_missing_table_field (text, text, text, text) IS 'Add a column of a given type to a table if it is missing';

-- *****************************************************************************

CREATE OR REPLACE FUNCTION public.alter_table_drop_column_if_exists( text, text, text ) RETURNS bool as
$$
	DECLARE
		p_namespace alias for $1;
		p_table     alias for $2;
		p_field     alias for $3;
		v_row       record;
		v_query     text;
	BEGIN
		SELECT 1 INTO v_row FROM pg_namespace n, pg_class c, pg_attribute a
			WHERE
				n.nspname = p_namespace
				AND c.relnamespace = n.oid
				AND c.relname = p_table
				AND a.attrelid = c.oid
				AND a.attname = p_field;
		IF FOUND THEN
			RAISE NOTICE 'Upgrade table %.% - drop field %', p_namespace, p_table, p_field;
			v_query := 'ALTER TABLE ' || p_namespace || '.' || p_table || ' DROP column ' || p_field || ';';
			EXECUTE v_query;
			RETURN 't';
		ELSE
			RETURN 'f';
		END IF;
	END;
$$
LANGUAGE plpgsql;

COMMENT ON FUNCTION public.add_missing_table_field (text, text, text, text) IS 'Drops a column from a table if it exists.';

SELECT alter_table_drop_column_if_exists( 'public', 'orientsstructs', 'rgorient' );

-- *****************************************************************************

-- A-t'on des vrais doublons ?
-- -> 1322 lignes de doublons pour cg93_20101203_20h46
--    88 avec statut_orient = 'Orienté', 1234 avec statut_orient = 'Non orienté'
-- -> 70 lignes de doublons pour cg66_20101217_eps
--    70 avec statut_orient = 'Orienté'

CREATE OR REPLACE FUNCTION public.dedoublonnage_orientsstructs() RETURNS bool as
$$
	DECLARE
		v_row		RECORD;
		v_doublon	RECORD;
		v_first_id	INTEGER;
	BEGIN
		FOR v_row IN
			SELECT
					orientsstructs.personne_id
				FROM orientsstructs
				WHERE
					orientsstructs.personne_id IN (
						SELECT
									DISTINCT( t.personne_id )
								FROM (
									SELECT
											COUNT(id) AS count,
											orientsstructs.personne_id,
											orientsstructs.typeorient_id,
											orientsstructs.structurereferente_id,
											orientsstructs.propo_algo,
											orientsstructs.valid_cg,
											orientsstructs.date_propo,
											orientsstructs.date_valid,
											orientsstructs.statut_orient,
											orientsstructs.date_impression,
											orientsstructs.daterelance,
											orientsstructs.statutrelance,
											orientsstructs.date_impression_relance,
											orientsstructs.referent_id,
											orientsstructs.etatorient
										FROM orientsstructs
										GROUP BY
											orientsstructs.personne_id,
											orientsstructs.typeorient_id,
											orientsstructs.structurereferente_id,
											orientsstructs.propo_algo,
											orientsstructs.valid_cg,
											orientsstructs.date_propo,
											orientsstructs.date_valid,
											orientsstructs.statut_orient,
											orientsstructs.date_impression,
											orientsstructs.daterelance,
											orientsstructs.statutrelance,
											orientsstructs.date_impression_relance,
											orientsstructs.referent_id,
											orientsstructs.etatorient
								) AS t
								WHERE t.count > 1
						)
		LOOP
			v_first_id := ( SELECT id FROM orientsstructs WHERE orientsstructs.personne_id = v_row.personne_id ORDER BY id LIMIT 1 );

			FOR v_doublon IN
				SELECT
						orientsstructs.id
					FROM orientsstructs
					WHERE
						orientsstructs.personne_id = v_row.personne_id
						AND orientsstructs.id <> v_first_id
						AND orientsstructs.id IN (
							SELECT
										DISTINCT( orientsstructs.id )
									FROM (
										SELECT
												COUNT(id) AS count,
												orientsstructs.personne_id,
												orientsstructs.typeorient_id,
												orientsstructs.structurereferente_id,
												orientsstructs.propo_algo,
												orientsstructs.valid_cg,
												orientsstructs.date_propo,
												orientsstructs.date_valid,
												orientsstructs.statut_orient,
												orientsstructs.date_impression,
												orientsstructs.daterelance,
												orientsstructs.statutrelance,
												orientsstructs.date_impression_relance,
												orientsstructs.referent_id,
												orientsstructs.etatorient
											FROM orientsstructs
											WHERE orientsstructs.personne_id = v_row.personne_id
											GROUP BY
												orientsstructs.personne_id,
												orientsstructs.typeorient_id,
												orientsstructs.structurereferente_id,
												orientsstructs.propo_algo,
												orientsstructs.valid_cg,
												orientsstructs.date_propo,
												orientsstructs.date_valid,
												orientsstructs.statut_orient,
												orientsstructs.date_impression,
												orientsstructs.daterelance,
												orientsstructs.statutrelance,
												orientsstructs.date_impression_relance,
												orientsstructs.referent_id,
												orientsstructs.etatorient
									) AS t
									WHERE t.count > 1
							)
			LOOP
				DELETE FROM pdfs WHERE pdfs.modele = 'Orientstruct' AND pdfs.fk_value = v_doublon.id;
				-- FIXME: pas delete mais UPDATE ?
				DELETE FROM orientsstructs_servicesinstructeurs WHERE orientsstructs_servicesinstructeurs.orientstruct_id = v_doublon.id;
				-- FIXME: pas delete mais UPDATE ?
				DELETE FROM parcoursdetectes WHERE parcoursdetectes.orientstruct_id = v_doublon.id;
				DELETE FROM orientsstructs WHERE orientsstructs.id = v_doublon.id;
			END LOOP;
		END LOOP;

		RETURN false;-- FIXME: retourne le nombre de suppressions ?
	END;
$$
LANGUAGE plpgsql;

SELECT dedoublonnage_orientsstructs();
DROP FUNCTION public.dedoublonnage_orientsstructs();

ALTER TABLE orientsstructs ADD COLUMN rgorient INTEGER DEFAULT NULL; -- INFO: rgorient SSI Orienté -> sinon, ça n'a pas de sens ? cf. Orientstruct;;beforeSave

UPDATE orientsstructs SET rgorient = NULL;
UPDATE orientsstructs
	SET rgorient = (
		SELECT ( COUNT(orientsstructspcd.id) + 1 )
			FROM orientsstructs AS orientsstructspcd
			WHERE orientsstructspcd.personne_id = orientsstructs.personne_id
				AND orientsstructspcd.id <> orientsstructs.id
				AND orientsstructs.date_valid IS NOT NULL
				AND orientsstructspcd.date_valid IS NOT NULL
				AND (
					orientsstructspcd.date_valid < orientsstructs.date_valid
					OR ( orientsstructspcd.date_valid = orientsstructs.date_valid AND orientsstructspcd.id < orientsstructs.id )
				)
				AND orientsstructs.statut_orient = 'Orienté'
				AND orientsstructspcd.statut_orient = 'Orienté'
	)
	WHERE
		orientsstructs.date_valid IS NOT NULL
		AND orientsstructs.statut_orient = 'Orienté';

CREATE UNIQUE INDEX orientsstructs_personne_id_rgorient_idx ON orientsstructs( personne_id, rgorient ) WHERE rgorient IS NOT NULL;

UPDATE orientsstructs
	SET statut_orient = 'Non orienté'
	WHERE typeorient_id IS NULL
		OR structurereferente_id IS NULL
		OR date_valid IS NULL;

ALTER TABLE orientsstructs ADD CONSTRAINT orientsstructs_statut_orient_oriente_rgorient_not_null_chk CHECK (
	statut_orient <> 'Orienté' OR ( statut_orient = 'Orienté' AND rgorient IS NOT NULL )
);

/*
-- FIXME: si statut_orient Orienté et date_valid -> valid_cg = true ?
-- En fait, il semblerait que l'on puisse supprimer la colonne (en modifiant le PHP)
app/models/critere.php:                    '"Orientstruct"."valid_cg"',
app/tests/fixtures/orientstruct_fixture.php:                            'valid_cg' => null,
app/tests/fixtures/orientstruct_fixture.php:                            'valid_cg' => '1',
app/tests/fixtures/orientstruct_fixture.php:                            'valid_cg' => '1',
app/tests/fixtures/orientstruct_fixture.php:                            'valid_cg' => '1',
app/tests/fixtures/orientstruct_fixture.php:                            'valid_cg' => '1',
app/tests/fixtures/orientstruct_fixture.php:                            'valid_cg' => '1',
app/tests/fixtures/orientstruct_fixture.php:                            'valid_cg' => '1',
app/tests/fixtures/orientstruct_fixture.php:                            'valid_cg' => '1',
app/tests/fixtures/orientstruct_fixture.php:                            'valid_cg' => '1',
app/controllers/dossierssimplifies_controller.php:                                $this->data['Orientstruct'][$key]['valid_cg'] = true;
app/controllers/orientsstructs_controller.php:                                  $this->data['Orientstruct']['valid_cg'] = true;
app/vendors/shells/refresh.php:                                                         'Orientstruct.valid_cg',
*/

CREATE TYPE type_statutoccupation AS ENUM ( 'proprietaire', 'locataire' );
ALTER TABLE dsps ADD COLUMN statutoccupation type_statutoccupation DEFAULT NULL;
ALTER TABLE dsps_revs ADD COLUMN statutoccupation type_statutoccupation DEFAULT NULL;

-- *****************************************************************************
-- Ajout de champs dans la table traitementspdos pour gérer la fiche de calcul
-- *****************************************************************************

DROP TYPE IF EXISTS TYPE_REGIMEFICHECALCUL CASCADE;
CREATE TYPE TYPE_REGIMEFICHECALCUL AS ENUM ( 'fagri', 'ragri', 'reel', 'microbic', 'microbicauto', 'microbnc' );

SELECT add_missing_table_field ('public', 'traitementspdos', 'regime', 'TYPE_REGIMEFICHECALCUL');
SELECT add_missing_table_field ('public', 'traitementspdos', 'saisonnier', 'TYPE_BOOLEANNUMBER');
SELECT add_missing_table_field ('public', 'traitementspdos', 'nrmrcs', 'VARCHAR(20)');
SELECT add_missing_table_field ('public', 'traitementspdos', 'dtdebutactivite', 'DATE');
SELECT add_missing_table_field ('public', 'traitementspdos', 'raisonsocial', 'VARCHAR(100)');
SELECT add_missing_table_field ('public', 'traitementspdos', 'dtdebutperiode', 'DATE');
SELECT add_missing_table_field ('public', 'traitementspdos', 'dtfinperiode', 'DATE');
SELECT add_missing_table_field ('public', 'traitementspdos', 'dtprisecompte', 'DATE');
SELECT add_missing_table_field ('public', 'traitementspdos', 'dtecheance', 'DATE');
SELECT add_missing_table_field ('public', 'traitementspdos', 'forfait', 'FLOAT');
SELECT add_missing_table_field ('public', 'traitementspdos', 'mtaidesub', 'FLOAT');
SELECT add_missing_table_field ('public', 'traitementspdos', 'chaffvnt', 'FLOAT');
SELECT add_missing_table_field ('public', 'traitementspdos', 'chaffsrv', 'FLOAT');
SELECT add_missing_table_field ('public', 'traitementspdos', 'benefoudef', 'FLOAT');
SELECT add_missing_table_field ('public', 'traitementspdos', 'ammortissements', 'FLOAT');
SELECT add_missing_table_field ('public', 'traitementspdos', 'salaireexploitant', 'FLOAT');
SELECT add_missing_table_field ('public', 'traitementspdos', 'provisionsnonded', 'FLOAT');
SELECT add_missing_table_field ('public', 'traitementspdos', 'moinsvaluescession', 'FLOAT');
SELECT add_missing_table_field ('public', 'traitementspdos', 'autrecorrection', 'FLOAT');

SELECT add_missing_table_field ('public', 'traitementspdos', 'nbmoisactivite', 'INTEGER');
SELECT add_missing_table_field ('public', 'traitementspdos', 'mnttotalpriscompte', 'FLOAT');
SELECT add_missing_table_field ('public', 'traitementspdos', 'revenus', 'FLOAT');
SELECT add_missing_table_field ('public', 'traitementspdos', 'benefpriscompte', 'FLOAT');

DROP TYPE IF EXISTS TYPE_AIDESUBVREINT CASCADE;
CREATE TYPE TYPE_AIDESUBVREINT AS ENUM ( 'aide1', 'aide2', 'subv1', 'subv2' );
SELECT add_missing_table_field ('public', 'traitementspdos', 'aidesubvreint', 'TYPE_AIDESUBVREINT');

-- *****************************************************************************
-- Création du nouvel enum pour l'état des dossier de PDO
-- *****************************************************************************

DROP TYPE IF EXISTS TYPE_ETATDOSSIERPDO CASCADE;
CREATE TYPE TYPE_ETATDOSSIERPDO AS ENUM ( 'attaffect', 'attinstr', 'instrencours', 'attval', 'decisionval', 'dossiertraite', 'attpj' );

SELECT add_missing_table_field ('public', 'propospdos', 'etatdossierpdo', 'TYPE_ETATDOSSIERPDO');

-- *****************************************************************************
-- Déplacement des champs de décisions de la PDO dans une autre table
-- *****************************************************************************

DROP TABLE IF EXISTS decisionspropospdos;
CREATE TABLE decisionspropospdos (
	id      				SERIAL NOT NULL PRIMARY KEY,
	datedecisionpdo			DATE,
	decisionpdo_id			INTEGER REFERENCES decisionspdos (id),
	commentairepdo			TEXT,
	isvalidation			type_booleannumber DEFAULT NULL,
	validationdecision		type_no DEFAULT NULL,
	datevalidationdecision	DATE,
	etatdossierpdo			TYPE_ETATDOSSIERPDO DEFAULT NULL,
	propopdo_id				INTEGER REFERENCES propospdos (id)
);

SELECT alter_table_drop_column_if_exists( 'public', 'propospdos', 'datedecisionpdo' );
SELECT alter_table_drop_column_if_exists( 'public', 'propospdos', 'decisionpdo_id' );
SELECT alter_table_drop_column_if_exists( 'public', 'propospdos', 'commentairepdo' );
SELECT alter_table_drop_column_if_exists( 'public', 'propospdos', 'isvalidation' );
SELECT alter_table_drop_column_if_exists( 'public', 'propospdos', 'validationdecision' );
SELECT alter_table_drop_column_if_exists( 'public', 'propospdos', 'datevalidationdecision' );

DROP INDEX IF EXISTS decisionspropospdos_decisionpdo_id_idx;
CREATE INDEX decisionspropospdos_decisionpdo_id_idx ON decisionspropospdos (decisionpdo_id);

DROP INDEX IF EXISTS decisionspropospdos_propopdo_id_idx;
CREATE INDEX decisionspropospdos_propopdo_id_idx ON decisionspropospdos (propopdo_id);

-- *****************************************************************************
-- Nouvelle structure pour les informations venant de Pôle Emploi
-- *****************************************************************************

-- INFO: voir http://postgresql.developpez.com/sources/?page=chaines
CREATE OR REPLACE FUNCTION "public"."noaccents_upper" (text) RETURNS text AS
$body$
	DECLARE
		st text;

	BEGIN
		-- On transforme les caractèes accentués et on passe en majuscule
		st:=translate($1,'aàäâeéèêëiïîoôöuùûücçAÀÄÂEÉÈÊËIÏÎOÔÖUÙÛÜCÇ','AAAAEEEEEIIIOOOUUUUCCAAAAEEEEEIIIOOOUUUUCC');
		st:=upper(st);

		return st;
	END;
$body$
LANGUAGE 'plpgsql' VOLATILE RETURNS NULL ON NULL INPUT SECURITY INVOKER;

-- FIXME: problèmes de minuscules et d'accents dans la table personnes --> mettre une contrainte ?
-- FIXME: problèmes de nom / prenom vides (pas NULL mais vides) dans la table personnes -> contrainte ?

-- Mise à jour sur la table personnes (nomnai, ... à NULL si une chaîne vide)
UPDATE personnes SET nomnai = NULL WHERE CHAR_LENGTH( TRIM( BOTH ' ' FROM nomnai ) ) = 0;
UPDATE personnes SET prenom2 = NULL WHERE CHAR_LENGTH( TRIM( BOTH ' ' FROM prenom2 ) ) = 0;
UPDATE personnes SET prenom3 = NULL WHERE CHAR_LENGTH( TRIM( BOTH ' ' FROM prenom3 ) ) = 0;

-- Mise à jour sur la table personnes (nom, ... -> en majuscules)
UPDATE personnes SET nom = public.noaccents_upper(nom) WHERE nom !~ '^([A-Z]|\-| |'')+$';
UPDATE personnes SET prenom = public.noaccents_upper(prenom) WHERE prenom !~ '^([A-Z]|\-| |'')+$';
UPDATE personnes SET nomnai = public.noaccents_upper(nomnai) WHERE ( nomnai IS NOT NULL AND nomnai !~ '^([A-Z]|\-| |'')+$' );
UPDATE personnes SET prenom2 = public.noaccents_upper(prenom2) WHERE ( prenom2 IS NOT NULL AND prenom2 !~ '^([A-Z]|\-| |'')+$' );
UPDATE personnes SET prenom3 = public.noaccents_upper(prenom3) WHERE ( prenom3 IS NOT NULL AND prenom3 !~ '^([A-Z]|\-| |'')+$' );

-- Mise à jour des anciennes tables tables concernant les inscriptions/cessations/radiations Pôle Emploi
UPDATE tempcessations SET nom = public.noaccents_upper(nom) WHERE nom !~ '^([A-Z]|\-| |'')+$';
UPDATE tempcessations SET prenom = public.noaccents_upper(nom) WHERE prenom !~ '^([A-Z]|\-| |'')+$';
UPDATE tempinscriptions SET nom = public.noaccents_upper(nom) WHERE nom !~ '^([A-Z]|\-| |'')+$';
UPDATE tempinscriptions SET prenom = public.noaccents_upper(nom) WHERE prenom !~ '^([A-Z]|\-| |'')+$';
UPDATE tempradiations SET nom = public.noaccents_upper(nom) WHERE nom !~ '^([A-Z]|\-| |'')+$';
UPDATE tempradiations SET prenom = public.noaccents_upper(nom) WHERE prenom !~ '^([A-Z]|\-| |'')+$';

-- Calcul de la clé du NIR (13 caractères) avec gestion des départements 2A et 2B
-- http://fr.wikipedia.org/wiki/Num%C3%A9ro_de_s%C3%A9curit%C3%A9_sociale_en_France#ancrage_E
CREATE OR REPLACE FUNCTION "public"."calcul_cle_nir" (text) RETURNS text AS
$body$
	DECLARE
		p_nir text;
		cle text;
		correction BIGINT;

	BEGIN
		correction:=0;
		p_nir:=$1;

		IF NOT nir_correct( p_nir ) THEN
			RETURN NULL;
		END IF;

		IF p_nir ~ '^.{6}(A|B)' THEN
			IF p_nir ~ '^.{6}A' THEN
				correction:=1000000;
			ELSE
				correction:=2000000;
			END IF;
			p_nir:=regexp_replace( p_nir, '(A|B)', '0' );
		END IF;

		cle:=LPAD( CAST( 97 - ( ( CAST( p_nir AS BIGINT ) - correction ) % 97 ) AS VARCHAR(13)), 2, '0' );
		RETURN cle;
	END;
$body$
LANGUAGE 'plpgsql' VOLATILE RETURNS NULL ON NULL INPUT SECURITY INVOKER;

/*
	Vérification du NIR sur 15 caractères
	INFO: http://fr.wikipedia.org/wiki/Num%C3%A9ro_de_s%C3%A9curit%C3%A9_sociale_en_France#Signification_des_chiffres_du_NIR
	----------------------------------------------------------------------------
	Tous:
		1) 1 à 5 ->		'^(1|2|7|8)[0-9]{2}(0[1-9]|[10-12]|[20-99])'
		2) 11 à 15 ->	'(00[1-9]|0[1-9][0-9]|[1-9][0-9][0-9]|)(0[1-9]|[10-97])$'
	A:
		1°) 6 à 7 ->	'^.{5}(0[1-9]|[10-95]|2A|2B)'
		2°) 8 à 10 ->	'^.{7}(00[1-9]|0[10-99]|[100-990])'
	B:
		1°) 6 à 8 ->	'^.{5}([970-989])'
		2°) 9 à 10 ->	'^.{8}(0[1-9]|[10-90])'
	C:
		1°) 6 à 7 ->	'^.{5}99'
		2°) 8 à 10 ->	'^.{7}(00[1-9]|0[10-99]|[100-990])'
*/

CREATE OR REPLACE FUNCTION "public"."nir_correct" (TEXT) RETURNS BOOLEAN AS
$body$
	DECLARE
		p_nir text;

	BEGIN
		p_nir:=$1;

		RETURN (
			CHAR_LENGTH( TRIM( BOTH ' ' FROM p_nir ) ) = 15
			AND (
				-- Tous les cas
				p_nir ~ '^(1|2|7|8)[0-9]{2}(0[1-9]|[10-12]|[20-99])'
				AND p_nir ~ '(00[1-9]|0[1-9][0-9]|[1-9][0-9][0-9]|)(0[1-9]|[10-97])$'
				AND (
					-- Cas A
					(
						p_nir ~ '^.{5}(0[1-9]|[10-95]|2A|2B)'
						AND p_nir ~ '^.{7}(00[1-9]|0[10-99]|[100-990])'
					)
					-- Cas B
					OR (
						p_nir ~ '^.{5}([970-989])'
						AND p_nir ~ '^.{8}(0[1-9]|[10-90])'
					)
					-- Cas C
					OR (
						p_nir ~ '^.{5}99'
						AND p_nir ~ '^.{7}(00[1-9]|0[10-99]|[100-990])'
					)
				)
				AND calcul_cle_nir( SUBSTRING( p_nir FROM 1 FOR 13 ) ) = SUBSTRING( p_nir FROM 14 FOR 2 )
			)
		);
	END;
$body$
LANGUAGE 'plpgsql';

-- SELECT COUNT(id) FROM personnes WHERE NOT nir_correct( nir );
-- SELECT COUNT(nir), nir FROM personnes WHERE NOT nir_correct( nir ) GROUP BY nir ORDER BY nir ASC;
-- SELECT COUNT(nir), nir FROM personnes WHERE NOT nir_correct( nir ) AND TRIM( BOTH ' ' FROM nir ) = '' GROUP BY nir ORDER BY nir ASC;
-- SELECT COUNT(nir), nir FROM personnes WHERE NOT nir_correct( nir ) AND TRIM( BOTH ' ' FROM nir ) <> '' AND nir !~ '[0-9]' GROUP BY nir ORDER BY nir ASC;
-- SELECT COUNT(nir), nir, LENGTH(nir), LENGTH(TRIM( BOTH ' ' FROM nir )) FROM personnes WHERE NOT nir_correct( nir ) AND LENGTH(TRIM( BOTH ' ' FROM nir )) <> 15 GROUP BY nir ORDER BY nir ASC;
-- SELECT COUNT(nir), nir, LENGTH(TRIM( BOTH ' ' FROM nir )) FROM personnes WHERE NOT nir_correct( nir ) AND LENGTH(TRIM( BOTH ' ' FROM nir )) = 15 GROUP BY nir ORDER BY nir ASC;

-- SELECT noaccents_upper(nom), LENGTH(noaccents_upper(nom)), TRIM( BOTH ' ' FROM noaccents_upper(nom) ), LENGTH(TRIM( BOTH ' ' FROM noaccents_upper(nom) )) FROM personnes WHERE noaccents_upper(nom) !~ '^[A-Z]([A-Z]|-| |'')*[A-Z]$';
-- SELECT noaccents_upper(prenom), LENGTH(noaccents_upper(prenom)), TRIM( BOTH ' ' FROM noaccents_upper(prenom) ), LENGTH(TRIM( BOTH ' ' FROM noaccents_upper(prenom) )) FROM personnes WHERE noaccents_upper(prenom) !~ '^[A-Z]([A-Z]|-| |'')*[A-Z]$';
-- SELECT noaccents_upper(nomnai), LENGTH(noaccents_upper(nomnai)), TRIM( BOTH ' ' FROM noaccents_upper(nomnai) ), LENGTH(TRIM( BOTH ' ' FROM noaccents_upper(nomnai) )) FROM personnes WHERE nomnai IS NOT NULL AND LENGTH(noaccents_upper(nomnai)) > 0 AND noaccents_upper(nomnai) !~ '^[A-Z]([A-Z]|-| |'')*[A-Z]$';

-- 0°) Nettoyage ---------------------------------------------------------------
DROP TABLE IF EXISTS historiqueetatspe CASCADE;
DROP TABLE IF EXISTS informationspe CASCADE;

DROP TYPE IF EXISTS TYPE_ETATPE CASCADE;

--

SELECT public.add_missing_table_field ( 'public', 'tempinscriptions', 'nir15', 'VARCHAR(15)');
UPDATE tempinscriptions SET nir15 = CASE WHEN ( nir_correct( nir || calcul_cle_nir( nir ) ) ) THEN nir || calcul_cle_nir( nir ) ELSE NULL END;
SELECT public.add_missing_table_field ( 'public', 'tempcessations', 'nir15', 'VARCHAR(15)');
UPDATE tempcessations SET nir15 = CASE WHEN ( nir_correct( nir || calcul_cle_nir( nir ) ) ) THEN nir || calcul_cle_nir( nir ) ELSE NULL END;
SELECT public.add_missing_table_field ( 'public', 'tempradiations', 'nir15', 'VARCHAR(15)');
UPDATE tempradiations SET nir15 = CASE WHEN ( nir_correct( nir || calcul_cle_nir( nir ) ) ) THEN nir || calcul_cle_nir( nir ) ELSE NULL END;

-- 1°) -------------------------------------------------------------------------
-- TODO: pourquoi une erreur avec les REFERENCES ?
CREATE TABLE informationspe (
	id				SERIAL NOT NULL PRIMARY KEY,
	nir				VARCHAR(15) DEFAULT NULL,
	nom				VARCHAR(50) DEFAULT NULL, -- FIXME: une personne a un nom NULL (id 50946) dans la table personnes (CG 66, 20101217_dump_webrsaCG66_rc9.sql.gz)
	prenom			VARCHAR(50) NOT NULL,
	dtnai			DATE NOT NULL
);

-- Contrainte sur le NIR qui doit être bien formé ou être NULL -- FIXME avec les valeurs réelles possibles, cf. fonction nir_correct
ALTER TABLE informationspe ADD CONSTRAINT informationspe_nir_correct_chk CHECK( nir IS NULL OR nir_correct( nir ) );
-- -- Test: doivent passer
-- INSERT INTO informationspe ( nir, nom, prenom, dtnai ) VALUES
-- 	( NULL, 'Foo', 'Bar', '2010-10-28' ),
-- 	( '123456789012345', 'Foo', 'Bar', '2010-10-28' );
-- -- Test: ne doit pas passer
-- INSERT INTO informationspe ( nir, nom, prenom, dtnai ) VALUES
-- 	( '123456 89012345', 'Foo', 'Bar', '2010-10-28' );

-- Indexes
CREATE INDEX informationspe_nir_idx ON informationspe ( nir varchar_pattern_ops );
-- FIXME: majuscules ?
CREATE INDEX informationspe_nom_idx ON informationspe ( nom varchar_pattern_ops );
CREATE INDEX informationspe_prenom_idx ON informationspe ( prenom varchar_pattern_ops );
CREATE INDEX informationspe_dtnai_idx ON informationspe ( dtnai );
CREATE UNIQUE INDEX informationspe_unique_tuple_idx ON informationspe ( nir, nom, prenom, dtnai );

COMMENT ON TABLE informationspe IS 'Liens entre Pôle Emploi et de supposés allocataires.';

-- 2°) Population de la table avec les valeurs des anciennes tables ------------

-- A partir des personnes déjà trouvées
INSERT INTO informationspe ( nir, nom, prenom, dtnai )
SELECT
		CASE WHEN ( nir_correct( personnes.nir ) ) THEN personnes.nir
			ELSE NULL
		END AS nir,
		personnes.nom,
		personnes.prenom,
		personnes.dtnai
	FROM infospoleemploi
	INNER JOIN personnes ON (
		infospoleemploi.personne_id = personnes.id
	)
	GROUP BY
		personnes.nir,
		personnes.nom,
		personnes.prenom,
		personnes.dtnai
	ORDER BY
		personnes.nir,
		personnes.nom,
		personnes.prenom,
		personnes.dtnai;

-- A partir des personnes pas encore trouvées (tables tempXXX)
INSERT INTO informationspe ( nir, nom, prenom, dtnai )
	SELECT
			nir15 AS nir,
			temp.nom,
			temp.prenom,
			temp.dtnai
		FROM (
			SELECT *
				FROM(
					SELECT
							nir15,
							nom,
							prenom,
							dtnai
						FROM tempcessations
					UNION
					SELECT
							nir15,
							nom,
							prenom,
							dtnai
						FROM tempradiations
					UNION
					SELECT
							nir15,
							nom,
							prenom,
							dtnai
						FROM tempinscriptions
				) AS tmptables
		) AS temp
		WHERE (
			SELECT
					COUNT(*)
				FROM informationspe
				WHERE (
						(
							informationspe.nir IS NOT NULL
							AND temp.nir15 IS NOT NULL
							AND informationspe.nir = temp.nir15
						)
						OR (
							informationspe.nom = temp.nom
							AND informationspe.prenom = temp.prenom
							AND informationspe.dtnai = temp.dtnai
						)
					)
		) = 0
		GROUP BY
			temp.nir15,
			temp.nom,
			temp.prenom,
			temp.dtnai;

-- 3°) -------------------------------------------------------------------------

CREATE TYPE TYPE_ETATPE AS ENUM ( 'cessation', 'inscription', 'radiation' );

CREATE TABLE historiqueetatspe (
	id					SERIAL NOT NULL PRIMARY KEY,
	informationpe_id	INTEGER NOT NULL REFERENCES informationspe(id) ON UPDATE CASCADE ON DELETE CASCADE,
	identifiantpe		VARCHAR(11) NOT NULL, -- FIXME: 11 ou 8 et 3 pour la structure ?
	date				DATE NOT NULL,
	etat				TYPE_ETATPE NOT NULL,
	code				VARCHAR(2) DEFAULT NULL,
	motif				VARCHAR(250) DEFAULT NULL
);

COMMENT ON TABLE historiqueetatspe IS 'Historique des états par lesquels passe un supposé allocataire à Pôle Emploi, avec l''identifiant PE associé.';

CREATE INDEX historiqueetatspe_informationpe_id_idx ON historiqueetatspe ( informationpe_id );
CREATE INDEX historiqueetatspe_identifiantpe_idx ON historiqueetatspe ( identifiantpe varchar_pattern_ops );
CREATE INDEX historiqueetatspe_date_idx ON historiqueetatspe ( date );
CREATE INDEX historiqueetatspe_etat_idx ON historiqueetatspe ( etat );
CREATE INDEX historiqueetatspe_code_idx ON historiqueetatspe ( code varchar_pattern_ops );
CREATE INDEX historiqueetatspe_motif_idx ON historiqueetatspe ( motif varchar_pattern_ops );
CREATE UNIQUE INDEX historiqueetatspe_unique_tuple_idx ON historiqueetatspe ( informationpe_id, identifiantpe, date, etat, code, motif );

-- 4°) Population de la table avec les valeurs des anciennes tables ------------
-- A partir des personnes déjà trouvées
-- FIXME: ici, les inscriptions, c'est ceux qui n'ont rien dans les autres dates
-- -> faut il rajouter les inscriptions de ceux qui ont quelque chose dans ces autres dates ?
INSERT INTO historiqueetatspe ( informationpe_id, identifiantpe, date, etat, code, motif )
	SELECT
			informationspe.id,
			infospoleemploi.identifiantpe,
			CASE
				WHEN infospoleemploi.datecessation IS NOT NULL THEN infospoleemploi.datecessation
				WHEN infospoleemploi.dateradiation IS NOT NULL THEN infospoleemploi.dateradiation
				WHEN infospoleemploi.dateinscription IS NOT NULL THEN infospoleemploi.dateinscription
			END AS date,
			CASE
				WHEN infospoleemploi.datecessation IS NOT NULL THEN CAST( 'cessation' AS TYPE_ETATPE )
				WHEN infospoleemploi.dateradiation IS NOT NULL THEN CAST( 'radiation' AS TYPE_ETATPE )
				WHEN infospoleemploi.dateinscription IS NOT NULL THEN CAST( 'inscription' AS TYPE_ETATPE )
			END AS etat,
			CASE
				WHEN infospoleemploi.datecessation IS NOT NULL THEN NULL
				WHEN infospoleemploi.dateradiation IS NOT NULL THEN NULL
				WHEN infospoleemploi.dateinscription IS NOT NULL THEN infospoleemploi.categoriepe
			END AS code,
			CASE
				WHEN infospoleemploi.datecessation IS NOT NULL THEN infospoleemploi.motifcessation
				WHEN infospoleemploi.dateradiation IS NOT NULL THEN infospoleemploi.motifradiation
				WHEN infospoleemploi.dateinscription IS NOT NULL THEN NULL
			END AS motif
		FROM infospoleemploi
			INNER JOIN personnes ON (
				personnes.id = infospoleemploi.personne_id
			)
			INNER JOIN informationspe ON (
				(
					informationspe.nir IS NOT NULL
					AND personnes.nir IS NOT NULL
					AND informationspe.nir = personnes.nir
				)
				OR
				(
					informationspe.nom = personnes.nom
					AND informationspe.prenom = personnes.prenom
					AND informationspe.dtnai = personnes.dtnai
				)
			)
		GROUP BY
			informationspe.id,
			infospoleemploi.identifiantpe,
			date,
			etat,
			code,
			motif;

-- A partir des personnes pas encore trouvées (tables tempXXX)
INSERT INTO historiqueetatspe ( informationpe_id, identifiantpe, date, etat, code, motif )
	SELECT
			informationspe.id,
			identifiantpe,
			date,
			etat,
			code,
			motif
		FROM(
			SELECT
					nir15,
					identifiantpe,
					nom,
					prenom,
					dtnai,
					CAST( 'cessation' AS TYPE_ETATPE ) AS etat,
					datecessation AS date,
					NULL AS code,
					motifcessation as motif
				FROM tempcessations
			UNION
			SELECT
					nir15,
					identifiantpe,
					nom,
					prenom,
					dtnai,
					CAST( 'radiation' AS TYPE_ETATPE ) AS etat,
					dateradiation AS date,
					NULL AS code,
					motifradiation as motif
				FROM tempradiations
			UNION
			SELECT
					nir15,
					identifiantpe,
					nom,
					prenom,
					dtnai,
					CAST( 'inscription' AS TYPE_ETATPE ) AS etat,
					dateinscription AS date,
					categoriepe AS code,
					NULL as motif
				FROM tempinscriptions
		) AS temp
			INNER JOIN informationspe ON (
				(
					informationspe.nir IS NOT NULL
					AND temp.nir15 IS NOT NULL
					AND informationspe.nir = temp.nir15
				)
				OR (
					informationspe.nom = temp.nom
					AND informationspe.prenom = temp.prenom
					AND informationspe.dtnai = temp.dtnai
				)
			)
		GROUP BY
			informationspe.id,
			identifiantpe,
			date,
			etat,
			code,
			motif;

-- 5°) Mise à jour des codes -- FIXME: tous les codes
UPDATE
	historiqueetatspe
	SET code = '90'
	WHERE code IS NULL
		AND etat = 'cessation'
		AND motif = 'ABSENCE AU CONTROLE (NON REPONSE A DAM)';

UPDATE
	historiqueetatspe
	SET code = 'CX'
	WHERE code IS NULL
		AND etat = 'radiation'
		AND motif = 'REFUS ACTION INSERTION SUSPENSION DE QUINZE JOURS';

UPDATE
	historiqueetatspe
	SET code = '92'
	WHERE code IS NULL
		AND etat = 'radiation'
		AND motif = 'NON REPONSE A CONVOCATION SUSPENSION DE DEUX MOIS';

UPDATE
	historiqueetatspe
	SET code = '8X'
	WHERE code IS NULL
		AND etat = 'radiation'
		AND motif = 'INSUFFISANCE DE RECHERCHE D''EMPLOI SUSPENSION DE QUINZE JOURS';

--

DROP INDEX IF EXISTS decisionspropospdos_datedecisionpdo_idx;
DROP INDEX IF EXISTS decisionspropospdos_datevalidationdecision_idx;
DROP INDEX IF EXISTS decisionspropospdos_etatdossierpdo_idx;
DROP INDEX IF EXISTS decisionspropospdos_isvalidation_idx;
DROP INDEX IF EXISTS decisionspropospdos_validationdecision_idx;
DROP INDEX IF EXISTS detailsressourcesmensuelles_ressourcesmensuelles_detailressourcemensuelle_id_idx;
DROP INDEX IF EXISTS detailsressourcesmensuelles_ressourcesmensuelles_ressourcemensuelle_id_idx;
DROP INDEX IF EXISTS dsps_statutoccupation_idx;
DROP INDEX IF EXISTS dsps_revs_statutoccupation_idx;
DROP INDEX IF EXISTS locsvehicinsert_pieceslocsvehicinsert_piecelocvehicinsert_id_idx;
DROP INDEX IF EXISTS regroupementszonesgeo_zonesgeographiques_regroupementzonegeo_id_idx;
DROP INDEX IF EXISTS regroupementszonesgeo_zonesgeographiques_zonegeographique_id_idx;
DROP INDEX IF EXISTS structuresreferentes_zonesgeographiques_structurereferente_id_idx;
DROP INDEX IF EXISTS traitementspdos_aidesubvreint_idx;
DROP INDEX IF EXISTS traitementspdos_dtdebutactivite_idx;
DROP INDEX IF EXISTS traitementspdos_dtdebutperiode_idx;
DROP INDEX IF EXISTS traitementspdos_dtecheance_idx;
DROP INDEX IF EXISTS traitementspdos_dtfinperiode_idx;
DROP INDEX IF EXISTS traitementspdos_dtprisecompte_idx;
DROP INDEX IF EXISTS traitementspdos_regime_idx;
DROP INDEX IF EXISTS traitementspdos_saisonnier_idx;

-- -----------------------------------------------------------------------------

CREATE INDEX decisionspropospdos_datedecisionpdo_idx ON decisionspropospdos (datedecisionpdo);
CREATE INDEX decisionspropospdos_datevalidationdecision_idx ON decisionspropospdos (datevalidationdecision);
CREATE INDEX decisionspropospdos_etatdossierpdo_idx ON decisionspropospdos (etatdossierpdo);
CREATE INDEX decisionspropospdos_isvalidation_idx ON decisionspropospdos (isvalidation);
CREATE INDEX decisionspropospdos_validationdecision_idx ON decisionspropospdos (validationdecision);
CREATE INDEX detailsressourcesmensuelles_ressourcesmensuelles_detailressourcemensuelle_id_idx ON detailsressourcesmensuelles_ressourcesmensuelles (detailressourcemensuelle_id);
CREATE INDEX detailsressourcesmensuelles_ressourcesmensuelles_ressourcemensuelle_id_idx ON detailsressourcesmensuelles_ressourcesmensuelles (ressourcemensuelle_id);
CREATE INDEX dsps_statutoccupation_idx ON dsps (statutoccupation);
CREATE INDEX dsps_revs_statutoccupation_idx ON dsps_revs (statutoccupation);
CREATE INDEX locsvehicinsert_pieceslocsvehicinsert_piecelocvehicinsert_id_idx ON locsvehicinsert_pieceslocsvehicinsert (piecelocvehicinsert_id);
CREATE INDEX regroupementszonesgeo_zonesgeographiques_regroupementzonegeo_id_idx ON regroupementszonesgeo_zonesgeographiques (regroupementzonegeo_id);
CREATE INDEX regroupementszonesgeo_zonesgeographiques_zonegeographique_id_idx ON regroupementszonesgeo_zonesgeographiques (zonegeographique_id);
CREATE INDEX structuresreferentes_zonesgeographiques_structurereferente_id_idx ON structuresreferentes_zonesgeographiques (structurereferente_id);
CREATE INDEX traitementspdos_aidesubvreint_idx ON traitementspdos (aidesubvreint);
CREATE INDEX traitementspdos_dtdebutactivite_idx ON traitementspdos (dtdebutactivite);
CREATE INDEX traitementspdos_dtdebutperiode_idx ON traitementspdos (dtdebutperiode);
CREATE INDEX traitementspdos_dtecheance_idx ON traitementspdos (dtecheance);
CREATE INDEX traitementspdos_dtfinperiode_idx ON traitementspdos (dtfinperiode);
CREATE INDEX traitementspdos_dtprisecompte_idx ON traitementspdos (dtprisecompte);
CREATE INDEX traitementspdos_regime_idx ON traitementspdos (regime);
CREATE INDEX traitementspdos_saisonnier_idx ON traitementspdos (saisonnier);

-- -----------------------------------------------------------------------------

SELECT public.add_missing_table_field ( 'public', 'aidesapres66', 'motifrejetequipe', 'TEXT');

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
