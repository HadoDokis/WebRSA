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

-- Population des motifs de sortie "Autres droits" du formulaire/tableau D2
INSERT INTO sortiesautresd2pdvs93 ( name, created, modified ) VALUES
	( 'Création d''activité', NOW(), NOW() ),
	( 'Accès à un emploi temporaire ou saisonnier (< ou = à 6 mois)', NOW(), NOW() ),
	( 'Accès à un contrat aidé', NOW(), NOW() ),
	( 'Accès à un emploi durable (plus de 6 mois)', NOW(), NOW() ),
	( 'Accès à une formation qualifiante rémunérée', NOW(), NOW() ),
	( 'Accès à une formation certifiée rémunérée', NOW(), NOW() ),
	( 'Accès à une procédure VAE', NOW(), NOW() ),
	( 'Retour en formation scolaire (après une rupture)', NOW(), NOW() ),
	( 'Accès à un emploi salarié SIAE (hors contrat aidé)', NOW(), NOW() ),
	( 'Maintien ou développement de l''emploi ou de l''activité', NOW(), NOW() );

-- Population des motifs de sortie "Emploi formation" du formulaire/tableau D2
INSERT INTO sortiesemploisd2pdvs93 ( name, created, modified ) VALUES
	( 'Indemnisation Pôle Emploi', NOW(), NOW() ),
	( 'Allocation Adulte Handicapé', NOW(), NOW() ),
	( 'Autre invalidité', NOW(), NOW() ),
	( 'Retraites', NOW(), NOW() ),
	( 'Autres', NOW(), NOW() );

-- *****************************************************************************
COMMIT;
-- *****************************************************************************