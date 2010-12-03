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

CREATE TYPE type_orgpayeur AS ENUM ( 'CAF', 'MSA' );
ALTER TABLE propospdos ADD COLUMN orgpayeur type_orgpayeur;

CREATE TYPE type_dateactive AS ENUM ( 'datedepart', 'datereception' );
ALTER TABLE descriptionspdos ADD COLUMN dateactive type_dateactive NOT NULL DEFAULT 'datedepart';
ALTER TABLE descriptionspdos ADD COLUMN declencheep type_booleannumber NOT NULL DEFAULT '0';

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
-- Modification pour reprendre l'ancien bilan de parcours du 66
-- *****************************************************************************

ALTER TABLE bilansparcours66 ADD COLUMN accordprojet type_booleannumber;
ALTER TABLE bilansparcours66 ADD COLUMN maintienorientsansep type_orient;
ALTER TABLE bilansparcours66 ADD COLUMN choixparcours type_choixparcours;
ALTER TABLE bilansparcours66 ADD COLUMN changementrefsansep type_no;
ALTER TABLE bilansparcours66 ADD COLUMN maintienorientparcours type_orient;
ALTER TABLE bilansparcours66 ADD COLUMN changementrefparcours type_no;
ALTER TABLE bilansparcours66 ADD COLUMN reorientation type_reorientation;
ALTER TABLE bilansparcours66 ADD COLUMN examenaudition type_type_demande;
ALTER TABLE bilansparcours66 ADD COLUMN maintienorientavisep type_orient;
ALTER TABLE bilansparcours66 ADD COLUMN changementrefeplocale type_no;
ALTER TABLE bilansparcours66 ADD COLUMN reorientationeplocale type_reorientation;
ALTER TABLE bilansparcours66 ADD COLUMN typeeplocale type_typeeplocale;
ALTER TABLE bilansparcours66 ADD COLUMN decisioncommission type_aviscommission;
ALTER TABLE bilansparcours66 ADD COLUMN decisioncoordonnateur type_aviscoordonnateur;
ALTER TABLE bilansparcours66 ADD COLUMN decisioncga type_aviscoordonnateur;

ALTER TABLE bilansparcours66 ADD COLUMN datebilan date;
ALTER TABLE bilansparcours66 ADD COLUMN observbenef TEXT;
ALTER TABLE bilansparcours66 ADD COLUMN objinit TEXT;
ALTER TABLE bilansparcours66 ADD COLUMN objatteint TEXT;
ALTER TABLE bilansparcours66 ADD COLUMN objnew TEXT;

-- *****************************************************************************
-- Fin modification
-- *****************************************************************************

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
