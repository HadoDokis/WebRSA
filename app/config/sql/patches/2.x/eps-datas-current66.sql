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

INSERT INTO regroupementseps ( name ) VALUES
	( 'Équipe pluridisciplinaire locale, commission Parcours' ),
	( 'Équipe pluridisciplinaire départementale' );

INSERT INTO eps ( name, regroupementep_id, saisineepbilanparcours66, saisineepdpdo66 ) VALUES
	( 'EP locale Parcours, Perpignan 1', 1, 'cg', 'nontraite' ),
	( 'EP départementale', 2, 'nontraite', 'cg' );

INSERT INTO fonctionsmembreseps ( name ) VALUES
-- 	( 'Chef de projet de ville' ),
	( 'Représentant de Pôle Emploi' ),
	( 'Chargé d''insertion' );

INSERT INTO membreseps ( fonctionmembreep_id, qual, nom, prenom ) VALUES
	( 1, 'Mlle.', 'Dupont', 'Anne' ),
	( 1, 'M.', 'Martin', 'Pierre' ),
	( 2, 'M.', 'Dubois', 'Alphonse' ),
	( 2, 'Mme.', 'Roland', 'Adeline' );

INSERT INTO eps_zonesgeographiques ( ep_id, zonegeographique_id ) VALUES
	( 1, 8 ); -- Perpignan 1

INSERT INTO motifsreorients ( name ) VALUES
	( 'Motif réorientation 1' ),
	( 'Motif réorientation 2' );

SELECT pg_catalog.setval('seanceseps_id_seq', 1, true);
INSERT INTO seanceseps VALUES ( 1, 1, 22, '2010-10-28 10:00:00', NULL );

TRUNCATE situationspdos CASCADE;
SELECT pg_catalog.setval('situationspdos_id_seq', ( SELECT COALESCE( max(situationspdos.id) + 1, 1 ) FROM situationspdos ), false);
INSERT INTO situationspdos (libelle) VALUES
	('Evaluation revenus non salariés'),
	('Evaluation revenus de capitaux placés ou non'),
	('Evaluation de revenus de capitaux mobiliers'),
	('Démission, disponibilité, congé sans solde'),
	('Eléments non déclarés'),
	('Refus de ctrl'),
	('Dispense pension alimentaire'),
	('Parcours scolaire'),
	('Parcours de stage'),
	('Neutralisation'),
	('Droit au déjour EEE'),
	('Droit au déjour Hors EEE'),
	('Révision du droit'),
	('Défaut de conclusion contrat'),
	('Non respect du contrat'),
	('Radiation list Pôle Emploi'),
	('Dérogation'),
	('Subsidiarité')
;

TRUNCATE statutspdos CASCADE;
SELECT pg_catalog.setval('statutspdos_id_seq', ( SELECT COALESCE( max(statutspdos.id) + 1, 1 ) FROM statutspdos ), false);
INSERT INTO statutspdos (libelle) VALUES
	('TI'),
	('Ex TI'),
	('Exploitant agricole'),
	('Ex exploitant agricole'),
	('Auto-entrepreneur'),
	('Ex auto-entrepreneur'),
	('Gérant de Sté'),
	('Ex gérant de Sté'),
	('EEE'),
	('Hors EEE'),
	('Salarié'),
	('Ex salarié'),
	('Chômeur indemnisé'),
	('Chômeur non indemnisé'),
	('Etudiant'),
	('Stagiaire non rémunéré'),
	('Stagiaire rémunéré')
;

TRUNCATE descriptionspdos CASCADE;
SELECT pg_catalog.setval('descriptionspdos_id_seq', ( SELECT COALESCE( max(descriptionspdos.id) + 1, 1 ) FROM descriptionspdos ), false);
INSERT INTO descriptionspdos (name, dateactive, declencheep) VALUES
	('Courrier à l\'allocataire', 'datedepart', '0'),
	('Pièces arrivées', 'datereception', '0'),
	('Courrier Révision de ressources', 'datedepart', '0'),
	('Enquête administrative demandée', 'datedepart', '0'),
	('Enquête administrative reçue', 'datereception', '0'),
	('Saisine EP Dépt', 'datedepart', '1')
;

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
