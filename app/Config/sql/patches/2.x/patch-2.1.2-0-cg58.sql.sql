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

-------------------------------------------------------------------------------------------------------------
-- 20120611: ajout d'indexes pour les nouvelles clés étrangères des tables de
-- thématiques de COV (CG 58).
-------------------------------------------------------------------------------------------------------------

/*SELECT
	*
	FROM proposcontratsinsertioncovs58 p1,  proposcontratsinsertioncovs58 p2
	WHERE
		p1.id <> p2.id
		AND p1.nvcontratinsertion_id = p2.nvcontratinsertion_id;

SELECT
	*
	FROM proposorientationscovs58 p1,  proposorientationscovs58 p2
	WHERE
		p1.id <> p2.id
		AND p1.nvorientstruct_id = p2.nvorientstruct_id;

SELECT
	*
	FROM proposnonorientationsproscovs58 p1,  proposnonorientationsproscovs58 p2
	WHERE
		p1.id <> p2.id
		AND p1.nvorientstruct_id = p2.nvorientstruct_id;
*/

--  id  | dossiercov58_id | typeorient_id | orientstruct_id | structurereferente_id | referent_id | datedemande | rgorient | covtypeorient_id | covstructurereferente_id | datevalidation | commentaire | nvorientstruct_id | id  | dossiercov58_id | typeorient_id | orientstruct_id | structurereferente_id | referent_id | datedemande | rgorient | covtypeorient_id | covstructurereferente_id | datevalidation | commentaire | nvorientstruct_id
-- -----+-----------------+---------------+-----------------+-----------------------+-------------+-------------+----------+------------------+--------------------------+----------------+-------------+-------------------+-----+-----------------+---------------+-----------------+-----------------------+-------------+-------------+----------+------------------+--------------------------+----------------+-------------+-------------------
--  189 |            1554 |             3 |            5251 |                    10 |             | 2012-09-04  |        2 |                  |                          |                |             |             12905 | 190 |            1555 |             3 |            5251 |                    10 |             | 2012-09-04  |        2 |                  |                          |                |             |             12905
--  190 |            1555 |             3 |            5251 |                    10 |             | 2012-09-04  |        2 |                  |                          |                |             |             12905 | 189 |            1554 |             3 |            5251 |                    10 |             | 2012-09-04  |        2 |                  |                          |                |             |             12905
-- (2 lignes)


-- 20121012: dédoublonnage des orientations pour le CG 58
DELETE FROM orientsstructs WHERE orientsstructs.id IN (
	SELECT
		o1.id
		FROM orientsstructs o1, orientsstructs o2
		WHERE
			o1.id > o2.id
			AND o1.personne_id = o2.personne_id
			AND o1.typeorient_id = o2.typeorient_id
			AND o1.structurereferente_id = o2.structurereferente_id
			AND o1.date_valid = o2.date_valid
			AND o1.statut_orient = o2.statut_orient
			AND o1.statutrelance = o2.statutrelance
			AND o1.etatorient = o2.etatorient
			AND o1.haspiecejointe = o2.haspiecejointe
			AND o1.origine = o2.origine
			AND o1.id NOT IN (
				SELECT bilansparcours66.orientstruct_id FROM bilansparcours66 WHERE bilansparcours66.orientstruct_id = o1.id
				UNION
				SELECT defautsinsertionseps66.orientstruct_id FROM defautsinsertionseps66 WHERE defautsinsertionseps66.orientstruct_id = o1.id
				UNION
				SELECT nonorientationsproseps58.orientstruct_id FROM nonorientationsproseps58 WHERE nonorientationsproseps58.orientstruct_id = o1.id
				UNION
				SELECT nonorientationsproseps66.orientstruct_id FROM nonorientationsproseps66 WHERE nonorientationsproseps66.orientstruct_id = o1.id
				UNION
				SELECT nonorientationsproseps93.orientstruct_id FROM nonorientationsproseps93 WHERE nonorientationsproseps93.orientstruct_id = o1.id
				UNION
				SELECT nonrespectssanctionseps93.orientstruct_id FROM nonrespectssanctionseps93 WHERE nonrespectssanctionseps93.orientstruct_id = o1.id
				UNION
				SELECT orientsstructs_servicesinstructeurs.orientstruct_id FROM orientsstructs_servicesinstructeurs WHERE orientsstructs_servicesinstructeurs.orientstruct_id = o1.id
				UNION
				SELECT proposnonorientationsproscovs58.orientstruct_id FROM proposnonorientationsproscovs58 WHERE proposnonorientationsproscovs58.orientstruct_id = o1.id
				UNION
				SELECT reorientationseps93.orientstruct_id FROM reorientationseps93 WHERE reorientationseps93.orientstruct_id = o1.id
			)
		ORDER BY o1.personne_id, o1.id
);

-- *****************************************************************************
COMMIT;
-- *****************************************************************************