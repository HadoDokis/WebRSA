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

ALTER TABLE seanceseps ALTER COLUMN structurereferente_id DROP NOT NULL;

ALTER TABLE nvsrsepsreorient66 ALTER COLUMN decision DROP NOT NULL;

ALTER TABLE propospdos ADD COLUMN serviceinstructeur_id INTEGER REFERENCES servicesinstructeurs (id);
ALTER TABLE propospdos ADD COLUMN created TIMESTAMP WITHOUT TIME ZONE;
ALTER TABLE propospdos ADD COLUMN modified TIMESTAMP WITHOUT TIME ZONE;

CREATE TYPE type_orgpayeur AS ENUM ( 'CAF', 'MSA' );
ALTER TABLE propospdos ADD COLUMN orgpayeur type_orgpayeur;

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

CREATE TYPE type_dateactive AS ENUM ( 'datedepart', 'datereception' );
ALTER TABLE descriptionspdos ADD COLUMN dateactive type_dateactive NOT NULL DEFAULT 'datedepart';
ALTER TABLE descriptionspdos ADD COLUMN declencheep type_booleannumber NOT NULL DEFAULT '0';

INSERT INTO descriptionspdos (name, dateactive, declencheep) VALUES
	('Courrier à l\'allocataire', 'datedepart', '0'),
	('Pièces arrivées', 'datereception', '0'),
	('Courrier Révision de ressources', 'datedepart', '0'),
	('Enquête administrative demandée', 'datedepart', '0'),
	('Enquête administrative reçue', 'datereception', '0'),
	('Saisine EP Dépt', 'datedepart', '1')
;

ALTER TABLE traitementspdos ADD COLUMN dateecheance DATE;
ALTER TABLE traitementspdos ADD COLUMN daterevision DATE;

ALTER TABLE traitementspdos ADD COLUMN personne_id INTEGER REFERENCES personnes (id);
ALTER TABLE traitementspdos ADD COLUMN ficheanalyse TEXT;

ALTER TABLE dossierseps ALTER COLUMN themeep TYPE VARCHAR(100);
DROP TYPE type_themeep;
CREATE TYPE type_themeep AS ENUM ( 'saisinesepsreorientsrs93', 'saisinesepsbilansparcours66', 'suspensionsreductionsallocations93', 'saisinesepdspdos66' );
ALTER TABLE dossierseps ALTER COLUMN themeep TYPE type_themeep USING CAST(themeep AS type_themeep);

CREATE TABLE saisinesepdspdos66 (
	id      				SERIAL NOT NULL PRIMARY KEY,
	dossierep_id			INTEGER DEFAULT NULL REFERENCES dossierseps(id) ON DELETE CASCADE ON UPDATE CASCADE,
	traitementpdo_id		INTEGER NOT NULL REFERENCES traitementspdos (id),
	created					TIMESTAMP WITHOUT TIME ZONE,
	modified				TIMESTAMP WITHOUT TIME ZONE
);

ALTER TABLE eps ADD COLUMN saisineepdpdo66 TYPE_NIVEAUDECISIONEP NOT NULL DEFAULT 'nontraite';

ALTER TABLE traitementspdos ADD COLUMN clos INTEGER NOT NULL DEFAULT 0;

CREATE TABLE nvsepdspdos66 (
	id      				SERIAL NOT NULL PRIMARY KEY,
	saisineepdpdo66_id		INTEGER NOT NULL REFERENCES saisinesepdspdos66(id) ON DELETE CASCADE ON UPDATE CASCADE,
	etape					TYPE_ETAPEDECISIONEP NOT NULL,
	decisionpdo_id			INTEGER REFERENCES decisionspdos (id),
	commentaire				TEXT DEFAULT NULL,
	created					TIMESTAMP WITHOUT TIME ZONE,
	modified				TIMESTAMP WITHOUT TIME ZONE
);

ALTER TABLE nvsepdspdos66 ADD COLUMN nonadmis type_nonadmis;
ALTER TABLE nvsepdspdos66 ADD COLUMN motifpdo VARCHAR(1);
ALTER TABLE nvsepdspdos66 ADD COLUMN datedecisionpdo DATE;

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
