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

/**
	FIXME: dans le fichier tableau, toutes les lignes n'ont pas
		- de Numero Convention Action
		- de Filiere
		- ont du texte informatif dans le Tel_Action
		- plusieurs lignes d'Intitulé d'Action
*/

/*INSERT INTO thematiquesfps93 ( type, name, created, modified ) VALUES
	( 'pdi', 'Prescription professionnelle', NOW(), NOW() ),
	( 'pdi', 'Prescription socio-professionnelle', NOW(), NOW() );

INSERT INTO categoriesfps93 ( thematiquefp93_id, name, created, modified ) VALUES
	( 1, 'Accompagnement à la création d''activité', NOW(), NOW() ),
	( 2, 'Remise à niveau', NOW(), NOW() );

INSERT INTO filieresfps93 ( categoriefp93_id, name, created, modified ) VALUES
	( 1, 'Métiers divers', NOW(), NOW() ),
	( 2, 'Métiers du gardiennage et de la sécurité', NOW(), NOW() );

INSERT INTO prestatairesfps93 ( name, created, modified ) VALUES
	( 'ADEPT (Association Départementale pour la Promotion des Tziganes)', NOW(), NOW() ),
	( 'AIR  - Association d''Intérêt Régional', NOW(), NOW() );

INSERT INTO actionsfps93 ( filierefp93_id, prestatairefp93_id, name, numconvention, annee, actif, created, modified ) VALUES
	( 1, 1, 'Accompagnement à la création d''entreprise', NULL, 2014, '1', NOW(), NOW() ),
	( 2, 2, ' Remise à niveau à visée professionnelle avec code de la route - Roissy', '93HGB13020', 2014, '1', NOW(), NOW() );*/

INSERT INTO modstransmsfps93 ( name, created, modified ) VALUES
	( 'remise au bénéficiaire', NOW(), NOW() ),
	( 'par fax', NOW(), NOW() ),
	( 'remise directement au partenaire', NOW(), NOW() ),
	( 'courrier postal', NOW(), NOW() ),
	( 'par mail', NOW(), NOW() );

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
