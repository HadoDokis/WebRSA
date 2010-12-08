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
CREATE TABLE relancesxxx (
	id					SERIAL NOT NULL,
	personne_id			INTEGER DEFAULT NULL REFERENCES personnes(id),
	propopdo_id			INTEGER DEFAULT NULL REFERENCES propospdos(id),
	tempradiation_id	INTEGER DEFAULT NULL REFERENCES tempradiations(id), -- FIXME à l'avenir ?
	--saisine_id 			-- saisine -> FIXME
	orientstruct_id		INTEGER DEFAULT NULL REFERENCES orientsstructs(id),
	contratinsertion_id	INTEGER DEFAULT NULL REFERENCES contratsinsertion(id),
	cui_id				INTEGER DEFAULT NULL REFERENCES cuis(id)
	-- ppae -- bool
);

-- Combien de dernières orientsstructs qui n'ont pas signé de contrat lié à cette orientation
-- TODO: when au lieu du count (pour les performances) ?
SELECT
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
	LIMIT 10;




CREATE TYPE type_statutoccupation AS ENUM ( 'proprietaire', 'locataire' );
ALTER TABLE dsps ADD COLUMN statutoccupation type_statutoccupation DEFAULT NULL;
ALTER TABLE dsps_revs ADD COLUMN statutoccupation type_statutoccupation DEFAULT NULL;


-- *****************************************************************************
COMMIT;
-- *****************************************************************************
