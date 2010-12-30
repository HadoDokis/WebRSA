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
returns bool as '
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

ALTER TABLE orientsstructs ADD COLUMN rgorient INTEGER DEFAULT NULL;
-- NULL ou 0 par défaut

UPDATE orientsstructs
	SET rgorient = (
		SELECT ( COUNT(orientsstructspcd.id) + 1 )
			FROM orientsstructs AS orientsstructspcd
			WHERE orientsstructspcd.personne_id = orientsstructs.personne_id
				AND orientsstructspcd.id <> orientsstructs.id
				AND orientsstructspcd.date_valid <= orientsstructs.date_valid
				AND orientsstructspcd.date_valid IS NOT NULL
				AND orientsstructspcd.statut_orient = 'Orienté'
	);

-- Statistiques sur les personnes non demandeurs ou non conjoints RSA possédant une entrée dans orientsstructs
/*SELECT
	COUNT(orientsstructs.id), orientsstructs.statut_orient, prestations.rolepers
	FROM orientsstructs
	INNER JOIN personnes ON personnes.id = orientsstructs.personne_id
	INNER JOIN prestations ON prestations.personne_id = personnes.id
	WHERE prestations.natprest = 'RSA' AND prestations.rolepers NOT IN ('DEM', 'CJT')
	GROUP BY orientsstructs.statut_orient, prestations.rolepers;*/

-- *****************************************************************************

-- A-t'on des orientsstructs qui ont été relancées ?
/*SELECT
		COUNT(orientsstructs.id)
	FROM orientsstructs
	WHERE ( orientsstructs.statutrelance <> 'E' OR orientsstructs.statutrelance IS NULL )
		OR orientsstructs.daterelance IS NOT NULL
		OR orientsstructs.date_impression_relance IS NOT NULL;*/


-- actuellement relancesdetectionscontrats93
/*CREATE TABLE relancesxxx (
	id					SERIAL NOT NULL,
	personne_id			INTEGER DEFAULT NULL REFERENCES personnes(id),
	propopdo_id			INTEGER DEFAULT NULL REFERENCES propospdos(id),
	tempradiation_id	INTEGER DEFAULT NULL REFERENCES tempradiations(id), -- FIXME à l'avenir ?
	--saisine_id 			-- saisine -> FIXME
	orientstruct_id		INTEGER DEFAULT NULL REFERENCES orientsstructs(id),
	contratinsertion_id	INTEGER DEFAULT NULL REFERENCES contratsinsertion(id),
	cui_id				INTEGER DEFAULT NULL REFERENCES cuis(id)
	-- ppae -- bool
);*/

-- Combien de dernières orientsstructs qui n'ont pas signé de contrat lié à cette orientation
-- TODO: when au lieu du count (pour les performances) ?
/*SELECT
		orientsstructs.personne_id,
		( DATE( NOW() ) - orientsstructs.date_valid ) AS nbjours
	FROM orientsstructs
	WHERE
		-- la dernière orientation
		orientsstructs.id IN (
			SELECT dernierorientsstructs.id
				FROM orientsstructs AS dernierorientsstructs
				WHERE dernierorientsstructs.personne_id = orientsstructs.personne_id
					AND dernierorientsstructs.statut_orient = 'Orienté'
					AND dernierorientsstructs.date_valid IS NOT NULL
				ORDER BY dernierorientsstructs.date_valid DESC
				LIMIT 1
		)
		-- Ne possédant pas de contratsinsertion "lié à cette orientation"
		AND (
			SELECT COUNT(id) FROM (
				SELECT
						contratsinsertion.id AS id,
						contratsinsertion.dd_ci,
						contratsinsertion.personne_id
					FROM contratsinsertion
					WHERE
						contratsinsertion.personne_id = orientsstructs.personne_id
						AND (
							contratsinsertion.dd_ci >= orientsstructs.date_valid
							OR contratsinsertion.datevalidation_ci >= orientsstructs.date_valid
						)
					ORDER BY contratsinsertion.dd_ci DESC
					LIMIT 1
			) AS dernierscontratsinsertion
		) = 0
		-- Ne possédant pas de cuis "lié à cette orientation"
		AND (
			SELECT COUNT(id) FROM (
				SELECT
						cuis.id AS id,
						cuis.datecontrat,
						cuis.personne_id
					FROM cuis
					WHERE
						cuis.personne_id = orientsstructs.personne_id
						AND (
							cuis.datecontrat >= orientsstructs.date_valid
							OR cuis.datevalidationcui >= orientsstructs.date_valid
						)
					ORDER BY cuis.datecontrat DESC
					LIMIT 1
			) AS dernierscuis
		) = 0
	LIMIT 10;*/




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

ALTER TABLE propospdos DROP COLUMN datedecisionpdo;
ALTER TABLE propospdos DROP COLUMN decisionpdo_id;
ALTER TABLE propospdos DROP COLUMN commentairepdo;
ALTER TABLE propospdos DROP COLUMN isvalidation;
ALTER TABLE propospdos DROP COLUMN validationdecision;
ALTER TABLE propospdos DROP COLUMN datevalidationdecision;

-- *****************************************************************************
-- Ajout dans le bilansparcours66
-- *****************************************************************************

DROP TYPE IF EXISTS TYPE_ACCOMPAGNEMENT CASCADE;
CREATE TYPE TYPE_ACCOMPAGNEMENT AS ENUM ( 'prepro', 'social' );

SELECT add_missing_table_field ('public', 'bilansparcours66', 'accompagnement', 'TYPE_ACCOMPAGNEMENT');

DROP TYPE IF EXISTS TYPE_TYPEFORMULAIRE CASCADE;
CREATE TYPE TYPE_TYPEFORMULAIRE AS ENUM ( 'cg', 'pe' );

SELECT add_missing_table_field ('public', 'bilansparcours66', 'typeformulaire', 'TYPE_TYPEFORMULAIRE');
SELECT add_missing_table_field ('public', 'bilansparcours66', 'textbilanparcours', 'TEXT');

-- *****************************************************************************
-- Nouvelle structure pour les informations venant de Pôle Emploi
-- *****************************************************************************

/*
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

-- Mise à jour des tables passées
UPDATE tempcessations SET nom = public.noaccents_upper(nom) WHERE nom !~ '^([A-Z]|\-| |'')+$';
UPDATE tempcessations SET prenom = public.noaccents_upper(nom) WHERE prenom !~ '^([A-Z]|\-| |'')+$';
UPDATE tempinscriptions SET nom = public.noaccents_upper(nom) WHERE nom !~ '^([A-Z]|\-| |'')+$';
UPDATE tempinscriptions SET prenom = public.noaccents_upper(nom) WHERE prenom !~ '^([A-Z]|\-| |'')+$';
UPDATE tempradiations SET nom = public.noaccents_upper(nom) WHERE nom !~ '^([A-Z]|\-| |'')+$';
UPDATE tempradiations SET prenom = public.noaccents_upper(nom) WHERE prenom !~ '^([A-Z]|\-| |'')+$';
*/

/*
	-- Entrées non en majuscules sans accents dans personnes
	SELECT
		nom,
		prenom,
		nomnai,
		prenom2,
		prenom3
	FROM personnes
	WHERE
		nom !~ '^([A-Z]|\-| |'')+$'
		OR prenom !~ '^([A-Z]|\-| |'')+$'
		OR ( nomnai IS NOT NULL AND CHAR_LENGTH( TRIM( BOTH ' ' FROM nomnai ) ) > 0 AND nomnai !~ '^([A-Z]|\-| |'')+$' )
		OR ( prenom2 IS NOT NULL AND CHAR_LENGTH( TRIM( BOTH ' ' FROM prenom2 ) ) > 0 AND prenom2 !~ '^([A-Z]|\-| |'')+$' )
		OR ( prenom3 IS NOT NULL AND CHAR_LENGTH( TRIM( BOTH ' ' FROM prenom3 ) ) > 0 AND prenom3 !~ '^([A-Z]|\-| |'')+$' );
*/

-- TODO: faire une fonction pour compléter / vérifier le NIR ?

CREATE OR REPLACE FUNCTION "public"."calcul_cle_nir" (text) RETURNS text AS
$body$
	DECLARE
		st text;

	BEGIN
		st:=LPAD( CAST( 97 - ( CAST( $1 AS BIGINT ) % 97 ) AS VARCHAR(13)), 2, '0' );
		return st;
	END;
$body$
LANGUAGE 'plpgsql' VOLATILE RETURNS NULL ON NULL INPUT SECURITY INVOKER;


-- 0°) Nettoyage ---------------------------------------------------------------
DROP TABLE IF EXISTS historiqueetatspe CASCADE;
DROP TABLE IF EXISTS informationspe CASCADE;

DROP TYPE IF EXISTS TYPE_ETATPE CASCADE;

-- 1°) -------------------------------------------------------------------------
-- TODO: pourquoi une erreur avec les REFERENCES ?
CREATE TABLE informationspe (
	id				SERIAL NOT NULL PRIMARY KEY,
	personne_id		INTEGER DEFAULT NULL REFERENCES personnes(id) ON UPDATE CASCADE ON DELETE CASCADE, -- Personne non trouvée -> NULL
	nir				VARCHAR(15) DEFAULT NULL,
	nom				VARCHAR(50) DEFAULT NULL, -- FIXME: une personne a un nom NULL (id 50946) dans la table personnes (CG 66, 20101217_dump_webrsaCG66_rc9.sql.gz)
	prenom			VARCHAR(50) NOT NULL,
	dtnai			DATE NOT NULL
);

-- Contrainte sur le NIR qui doit être bien formé ou être NULL -- FIXME avec les valeurs réelles possibles
ALTER TABLE informationspe ADD CONSTRAINT informationspe_nir_correct_chk CHECK( nir IS NULL OR nir ~* '^[0-9]{15}$' );
-- -- Test: doivent passer
-- INSERT INTO informationspe ( personne_id, nir, nom, prenom, dtnai ) VALUES
-- 	( ( SELECT id FROM personnes ORDER BY id ASC LIMIT 1 ) , NULL, 'Foo', 'Bar', '2010-10-28' ),
-- 	( ( SELECT id FROM personnes ORDER BY id DESC LIMIT 1 ) , '123456789012345', 'Foo', 'Bar', '2010-10-28' );
-- -- Test: ne doit pas passer
-- INSERT INTO informationspe ( personne_id, nir, nom, prenom, dtnai ) VALUES
-- 	( ( SELECT id FROM personnes ORDER BY id DESC LIMIT 1 ) , '123456 89012345', 'Foo', 'Bar', '2010-10-28' );

-- Indexes
CREATE UNIQUE INDEX informationspe_personne_id_idx ON informationspe ( personne_id );
CREATE INDEX informationspe_nir_idx ON informationspe ( nir varchar_pattern_ops );
-- FIXME: majuscules ?
CREATE INDEX informationspe_nom_idx ON informationspe ( nom varchar_pattern_ops );
CREATE INDEX informationspe_prenom_idx ON informationspe ( prenom varchar_pattern_ops );
CREATE INDEX informationspe_dtnai_idx ON informationspe ( dtnai );
-- FIXME: il ne faudrait pas avoir besoin de personne_id dans l'index unique, mais les données déjà en base ne sont pas toujours bonnes
CREATE UNIQUE INDEX informationspe_unique_tuple_idx ON informationspe ( personne_id, nir, nom, prenom, dtnai );

COMMENT ON TABLE informationspe IS 'Liens entre Pôle Emploi et de supposés allocataires.';

-- 2°) Population de la table avec les valeurs des anciennes tables ------------

-- A partir des personnes déjà trouvées
INSERT INTO informationspe ( personne_id, nir, nom, prenom, dtnai )
SELECT
		infospoleemploi.personne_id,
-- 		personnes.nir,
		CASE WHEN ( personnes.nir ~* '^[0-9]{13}$' ) THEN personnes.nir || calcul_cle_nir( personnes.nir )
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
		infospoleemploi.personne_id,
		personnes.nir,
		personnes.nom,
		personnes.prenom,
		personnes.dtnai
	ORDER BY
		infospoleemploi.personne_id,
		personnes.nir,
		personnes.nom,
		personnes.prenom,
		personnes.dtnai;

-- A partir des personnes pas encore trouvées (tables tempXXX)
INSERT INTO informationspe ( nir, nom, prenom, dtnai )
	SELECT
			-- FIXME: est-ce le bon calcul d'une clé de NIR ?
			CASE WHEN ( temp.nir ~* '^[0-9]{13}$' ) THEN temp.nir || calcul_cle_nir( temp.nir )
				ELSE NULL
			END AS nir,
			temp.nom,
			temp.prenom,
			temp.dtnai
		FROM (
			SELECT *
				FROM(
					SELECT
							nir,
							nom,
							prenom,
							dtnai
						FROM tempcessations
					UNION
					SELECT
							nir,
							nom,
							prenom,
							dtnai
						FROM tempradiations
					UNION
					SELECT
							nir,
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
							informationspe.nir = temp.nir
							AND informationspe.nir IS NOT NULL
							AND temp.nir IS NOT NULL
						)
						OR (
							informationspe.nom = temp.nom
							AND informationspe.prenom = temp.prenom
							AND informationspe.dtnai = temp.dtnai
						)
					)
		) = 0
		GROUP BY
			temp.nir,
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
			INNER JOIN informationspe ON (
				informationspe.personne_id = infospoleemploi.personne_id
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
					CASE WHEN ( nir ~* '^[0-9]{13}$' ) THEN nir || calcul_cle_nir( nir )
						ELSE NULL
					END AS nir,
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
					CASE WHEN ( nir ~* '^[0-9]{13}$' ) THEN nir || calcul_cle_nir( nir )
						ELSE NULL
					END AS nir,
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
					CASE WHEN ( nir ~* '^[0-9]{13}$' ) THEN nir || calcul_cle_nir( nir )
						ELSE NULL
					END AS nir,
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
					informationspe.nir = temp.nir
					AND informationspe.nir IS NOT NULL
					AND temp.nir IS NOT NULL
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

-- "Doublons" --> 672
/*SELECT
		i.*
	FROM (
		SELECT
				COUNT(informationspe.id) AS count,
		-- 		informationspe.personne_id,
				informationspe.nir,
				informationspe.nom,
				informationspe.prenom,
				informationspe.dtnai
			FROM informationspe
			GROUP BY
		-- 		informationspe.personne_id,
				informationspe.nir,
				informationspe.nom,
				informationspe.prenom,
				informationspe.dtnai
	) AS i
	WHERE i.count > 1
	ORDER BY i.count DESC

-- FIXME: 3 x avec le rôle DEM RSA
-- FIXME: 2 dossiers différents: 1 en droit 6 (clos/FIXME), 2 personnes DEM pour l'autre dossier
SELECT
		informationspe.*,
		prestations.*,
		situationsdossiersrsa.*
	FROM informationspe
		INNER JOIN prestations ON (
			prestations.personne_id = informationspe.personne_id
			AND prestations.natprest = 'RSA'
		)
		INNER JOIN personnes ON (
			personnes.id = informationspe.personne_id
		)
		INNER JOIN foyers ON (
			personnes.foyer_id = foyers.id
		)
		INNER JOIN dossiers ON (
			foyers.dossier_id = dossiers.id
		)
		INNER JOIN situationsdossiersrsa ON (
			situationsdossiersrsa.dossier_id = dossiers.id
		)
	WHERE
		informationspe.nom = ( SELECT nom FROM personnes WHERE personnes.id = 49646 )
		AND informationspe.prenom = ( SELECT prenom FROM personnes WHERE personnes.id = 49646 )
		AND informationspe.dtnai = ( SELECT dtnai FROM personnes WHERE personnes.id = 49646 )

-- EXEMPLE: dernière information du parcours PE d'un allocataire
SELECT
		historiqueetatspe.identifiantpe,
		historiqueetatspe.date,
		historiqueetatspe.etat,
		historiqueetatspe.code,
		historiqueetatspe.motif
	FROM historiqueetatspe
	WHERE historiqueetatspe.informationpe_id IN (
		SELECT
				informationspe.id
			FROM informationspe
			WHERE
				informationspe.nom = ( SELECT nom FROM personnes WHERE personnes.id = 49646 )
				AND informationspe.prenom = ( SELECT prenom FROM personnes WHERE personnes.id = 49646 )
				AND informationspe.dtnai = ( SELECT dtnai FROM personnes WHERE personnes.id = 49646 )
	)
	GROUP BY
		historiqueetatspe.identifiantpe,
		historiqueetatspe.date,
		historiqueetatspe.etat,
		historiqueetatspe.code,
		historiqueetatspe.motif
	ORDER BY historiqueetatspe.date DESC
	LIMIT 1

-- EXEMPLE: dernière information venant de Pôle Emploi pour les allocataires
SELECT
		COUNT(*),
-- 		informationspe.nir,
		informationspe.nom,
		informationspe.prenom,
		informationspe.dtnai
-- 		historiqueetatspe.date,
-- 		historiqueetatspe.etat
	FROM informationspe
		INNER JOIN historiqueetatspe ON (
			historiqueetatspe.informationpe_id = informationspe.id
		)
	WHERE
		historiqueetatspe.id IN (
			SELECT h.id
				FROM historiqueetatspe AS h
				WHERE h.informationpe_id = informationspe.id
				ORDER BY h.date DESC
				LIMIT 1
		)
	GROUP BY
-- 		nir,
		nom,
		prenom,
		dtnai
-- 		date,
-- 		etat
*/

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
