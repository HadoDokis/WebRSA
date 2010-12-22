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
COMMIT;
-- *****************************************************************************
