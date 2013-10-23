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

-- Population des motifs de sortie de l'accompagnement du formulaire/tableau D2
INSERT INTO sortiesaccompagnementsd2pdvs93 ( name, parent_id, created, modified ) VALUES
	( 'Sortie "Emploi formation"', NULL, NOW(), NOW() ),
	( 'Sortie "Autres droits"', NULL, NOW(), NOW() ),
	-- Motifs de sortie "Autres droits" du formulaire/tableau D2
	( 'Création d''activité', 2, NOW(), NOW() ),
	( 'Accès à un emploi temporaire ou saisonnier (< ou = à 6 mois)', 2, NOW(), NOW() ),
	( 'Accès à un contrat aidé', 2, NOW(), NOW() ),
	( 'Accès à un emploi durable (plus de 6 mois)', 2, NOW(), NOW() ),
	( 'Accès à une formation qualifiante rémunérée', 2, NOW(), NOW() ),
	( 'Accès à une formation certifiée rémunérée', 2, NOW(), NOW() ),
	( 'Accès à une procédure VAE', 2, NOW(), NOW() ),
	( 'Retour en formation scolaire (après une rupture)', 2, NOW(), NOW() ),
	( 'Accès à un emploi salarié SIAE (hors contrat aidé)', 2, NOW(), NOW() ),
	( 'Maintien ou développement de l''emploi ou de l''activité', 2, NOW(), NOW() ),
	-- Motifs de sortie "Emploi formation" du formulaire/tableau D2
	( 'Indemnisation Pôle Emploi', 1, NOW(), NOW() ),
	( 'Allocation Adulte Handicapé', 1, NOW(), NOW() ),
	( 'Autre invalidité', 1, NOW(), NOW() ),
	( 'Retraites', 1, NOW(), NOW() ),
	( 'Autres', 1, NOW(), NOW() );

-- *****************************************************************************
COMMIT;
-- *****************************************************************************