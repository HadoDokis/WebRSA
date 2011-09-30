SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = notice;
SET escape_string_warning = off;
SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

-- *****************************************************************************
BEGIN;
-- *****************************************************************************

UPDATE contratsinsertion
	SET rg_ci = NULL;

UPDATE contratsinsertion
	SET rg_ci = (
		SELECT ( COUNT(contratsinsertionpcd.id) + 1 )
			FROM contratsinsertion AS contratsinsertionpcd
			WHERE
				contratsinsertionpcd.personne_id = contratsinsertion.personne_id
				AND contratsinsertionpcd.id <> contratsinsertion.id
				AND contratsinsertionpcd.decision_ci = 'V'
				AND contratsinsertionpcd.dd_ci IS NOT NULL
				AND contratsinsertionpcd.dd_ci < contratsinsertion.dd_ci
				AND (
					contratsinsertion.positioncer IS NULL
					OR contratsinsertion.positioncer <> 'annule'
				)
	)
	WHERE
		contratsinsertion.dd_ci IS NOT NULL
		AND contratsinsertion.decision_ci = 'V'
		AND (
			contratsinsertion.positioncer IS NULL
			OR contratsinsertion.positioncer <> 'annule'
		);

UPDATE contratsinsertion
	SET num_contrat = (
		CASE WHEN rg_ci IS NULL THEN NULL
		WHEN rg_ci = 1 THEN 'PRE'
		ELSE 'REN' END
	)::type_num_contrat;

-- cg66_20110706_eps: 682
-- SELECT
-- 			COUNT(*)
-- 		FROM contratsinsertion
-- 		WHERE
-- 			( contratsinsertion.rg_ci <> 1 AND contratsinsertion.num_contrat = 'PRE' )
-- 			OR ( contratsinsertion.rg_ci = 1 AND contratsinsertion.num_contrat = 'REN' )
-- 		LIMIT 10;

-- Personnes possédant plusieurs contrats validés de mêmes rangs
-- cg66_20110706_eps: 61
-- SELECT
-- 		DISTINCT contratsinsertion.personne_id
-- 	FROM
-- 			contratsinsertion,
-- 			contratsinsertion AS contratsinsertionpcd
-- 	WHERE
-- 		contratsinsertion.rg_ci = contratsinsertionpcd.rg_ci
-- 		AND contratsinsertion.id <> contratsinsertionpcd.id
-- 		AND contratsinsertion.personne_id = contratsinsertionpcd.personne_id
-- 		AND contratsinsertion.decision_ci = contratsinsertionpcd.decision_ci
-- 		AND contratsinsertion.decision_ci = 'V'
-- 	LIMIT 10;

-- Quelles sont les CER dont la position n'est pas finale, mais qui appartiennent à dossier dont le droit est clos
-- SELECT
-- 		*
-- 	FROM contratsinsertion
-- 	WHERE
-- 		contratsinsertion.personne_id IN (
-- 			SELECT personnes.id
-- 				FROM personnes
-- 					INNER JOIN foyers ON ( foyers.id = personnes.foyer_id )
-- 					INNER JOIN dossiers ON ( dossiers.id = foyers.dossier_id )
-- 					INNER JOIN situationsdossiersrsa ON ( dossiers.id = situationsdossiersrsa.dossier_id )
-- 				WHERE
-- 					personnes.id = contratsinsertion.personne_id
-- 					AND situationsdossiersrsa.etatdosrsa IN ( '5', '6' )
-- 		)
-- 		AND (
-- 			contratsinsertion.positioncer IS NULL
-- 			OR contratsinsertion.positioncer IN ( 'encours', 'attvalid', 'encoursbilan', 'attrenouv' )
-- 		)
-- 	LIMIT 10;


-- *****************************************************************************
COMMIT;
-- *****************************************************************************