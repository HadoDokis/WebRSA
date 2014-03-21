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

-- 2939
INSERT INTO fichesprescriptions93 (
	personne_id,
	actionfp93_id,
	referent_id,
	dd_action,
	df_action,
	objet,
	date_signature,
	benef_retour_presente,
	personne_retenue,
	personne_recue,
	retour_nom_partenaire,
	documentbeneffp93_autre,
	personne_nonintegre_autre,
	personne_a_integre,
	statut,
	date_transmission,
	date_retour,
	created,
	modified
)
SELECT
		actionscandidats_personnes.personne_id,
		/*fixme_id*/1,
		actionscandidats_personnes.referent_id,
		actionscandidats.ddaction,
		actionscandidats.dfaction,
		actionscandidats_personnes.motifdemande,
		actionscandidats_personnes.datesignature,
		( CASE WHEN actionscandidats_personnes.bilanvenu = 'VEN' THEN 'oui' WHEN actionscandidats_personnes.bilanvenu = 'NVE' THEN 'non' ELSE NULL END ),
		( CASE WHEN actionscandidats_personnes.bilanretenu = 'RET' THEN '1' WHEN actionscandidats_personnes.bilanretenu = 'NRE' THEN '0' ELSE NULL END ),
		( CASE WHEN actionscandidats_personnes.bilanrecu = 'O' THEN '1' WHEN actionscandidats_personnes.bilanrecu = 'N' THEN '0' ELSE NULL END ),
		actionscandidats_personnes.personnerecu,
		actionscandidats_personnes.autrepiece,
		actionscandidats_personnes.precisionmotif,
		( CASE WHEN actionscandidats_personnes.integrationaction = 'O' THEN '1' WHEN actionscandidats_personnes.integrationaction = 'N' THEN '0' ELSE NULL END ),
		(
			CASE
				WHEN actionscandidats_personnes.positionfiche = 'annule' THEN '99annulee'
				WHEN actionscandidats_personnes.bilanrecu = 'O' THEN '05suivi_renseigne'
				-- WHEN actionscandidats_personnes.date_retour IS NOT NULL THEN '04effectivite_renseignee' -- FIXME
				-- WHEN actionscandidats_personnes.date_transmission IS NOT NULL THEN '03transmise_partenaire' -- FIXME
				WHEN actionscandidats_personnes.datesignature IS NOT NULL THEN '02signee'
				ELSE '01renseignee'
			END
		),
		actionscandidats_personnes.ddaction,
		actionscandidats_personnes.dfaction,
		LEAST( actionscandidats_personnes.ddaction, actionscandidats_personnes.dfaction, actionscandidats_personnes.datesignature, actionscandidats_personnes.datebilan, actionscandidats_personnes.daterecu, actionscandidats_personnes.sortiele ),
		GREATEST( actionscandidats_personnes.ddaction, actionscandidats_personnes.dfaction, actionscandidats_personnes.datesignature, actionscandidats_personnes.datebilan, actionscandidats_personnes.daterecu, actionscandidats_personnes.sortiele )
	FROM actionscandidats_personnes
		INNER JOIN actionscandidats ON ( actionscandidats_personnes.actioncandidat_id = actionscandidats.id );

-- *****************************************************************************
COMMIT;
-- *****************************************************************************