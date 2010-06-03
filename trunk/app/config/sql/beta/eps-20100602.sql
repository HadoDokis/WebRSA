/**
* TODO: création des indexes
*/

BEGIN;

-- -----------------------------------------------------------------------------

-- Ancienne version des eps
-- DROP TABLE IF EXISTS precosreorients;
-- DROP TABLE IF EXISTS decisionsparcours;
-- DROP TABLE IF EXISTS parcoursdetectes;
-- DROP TABLE IF EXISTS eps_partseps;
-- DROP TABLE IF EXISTS demandesreorient;
-- DROP TABLE IF EXISTS eps;
-- DROP TABLE IF EXISTS partseps;
-- DROP TABLE IF EXISTS rolespartseps;
-- DROP TABLE IF EXISTS motifsdemsreorients;
-- DROP TYPE IF EXISTS type_presenceep;
-- DROP TYPE IF EXISTS type_rolereorient;
-- DROP TYPE IF EXISTS type_roleparcours;

-- Nouvelle version des eps
DROP TABLE IF EXISTS decisionsreorient;
DROP TABLE IF EXISTS sceanceseps_demandesreorient;
DROP TABLE IF EXISTS demandesreorient;
DROP TABLE IF EXISTS motifsdemsreorients;
DROP TYPE IF EXISTS type_accordconcertation_reorient;
DROP TYPE IF EXISTS type_etapedecisionep;
DROP TYPE IF EXISTS type_decisionep;
DROP TABLE IF EXISTS partseps_sceanceseps;
DROP TYPE IF EXISTS type_presenceep;
DROP TYPE IF EXISTS type_reponseinvitationep;
DROP TABLE IF EXISTS sceanceseps;
DROP TYPE IF EXISTS type_traitementthemeep;
DROP TABLE IF EXISTS eps_zonesgeographiques;
DROP TABLE IF EXISTS partseps;
DROP TYPE IF EXISTS type_rolepartep;
DROP TABLE IF EXISTS fonctionspartseps;
DROP TABLE IF EXISTS eps;
DROP TYPE IF EXISTS type_themeep;

-- -----------------------------------------------------------------------------

/**
* EPs / participants / scéances EP
*/

CREATE TYPE type_themeep AS ENUM ( 'reorientation' );

CREATE TABLE eps (
	id								SERIAL NOT NULL PRIMARY KEY,
	name							VARCHAR(255) NOT NULL
);

CREATE TABLE fonctionspartseps (
	id								SERIAL NOT NULL PRIMARY KEY,
	name							VARCHAR(255) NOT NULL
);

CREATE TYPE type_rolepartep AS ENUM ( 'titulaire', 'suppleant' );

CREATE TABLE partseps (
	id								SERIAL NOT NULL PRIMARY KEY,
	qual							VARCHAR(3) NOT NULL,
	nom								VARCHAR(255) NOT NULL,
	prenom							VARCHAR(255) NOT NULL,
	tel								VARCHAR(14) DEFAULT NULL,
	email							VARCHAR(255) DEFAULT NULL,
	ep_id							INTEGER NOT NULL REFERENCES eps(id),
	fonctionpartep_id				INTEGER NOT NULL REFERENCES fonctionspartseps(id),
	rolepartep						type_rolepartep NOT NULL
);

CREATE TABLE eps_zonesgeographiques (
	id								SERIAL NOT NULL PRIMARY KEY,
	ep_id							INTEGER NOT NULL REFERENCES eps(id),
	zonegeographique_id				INTEGER NOT NULL REFERENCES zonesgeographiques(id)
);

CREATE TYPE type_traitementthemeep AS ENUM ( 'nontraite', 'decisionep', 'decisioncg' );

CREATE TABLE sceanceseps (
	id								SERIAL NOT NULL PRIMARY KEY,
	ep_id							INTEGER NOT NULL REFERENCES eps(id),
	structurereferente_id			INTEGER NOT NULL REFERENCES structuresreferentes(id),
	datesceance						TIMESTAMP WITHOUT TIME ZONE NOT NULL,
	finaliseeep						type_booleannumber DEFAULT NULL,
	finaliseecg						type_booleannumber DEFAULT NULL,
	-- themes
	reorientation					type_traitementthemeep NOT NULL
	-- ... + les autres
);

CREATE TYPE type_reponseinvitationep AS ENUM ( 'confirme', 'decline', 'non_renseigne' );
CREATE TYPE type_presenceep AS ENUM ( 'present', 'absent', 'excuse', 'remplace' );

CREATE TABLE partseps_sceanceseps (
	id								SERIAL NOT NULL PRIMARY KEY,
	partep_id						INTEGER NOT NULL REFERENCES partseps(id),
	sceanceep_id					INTEGER NOT NULL REFERENCES sceanceseps(id),
	reponseinvitation				type_reponseinvitationep DEFAULT 'non_renseigne',
	presence						type_presenceep DEFAULT NULL,
	partep_remplacant_id			INTEGER DEFAULT NULL REFERENCES partseps(id)
);

CREATE TYPE type_decisionep AS ENUM ( 'accord', 'refus', 'report' );
CREATE TYPE type_etapedecisionep AS ENUM ( 'ep', 'cg' );

/**
* Demandes de réorientation
*/

CREATE TYPE type_accordconcertation_reorient AS ENUM ( 'attente', 'accord', 'pasaccord' );

CREATE TABLE motifsdemsreorients (
	id		SERIAL NOT NULL PRIMARY KEY,
	name	VARCHAR(255) NOT NULL
);

CREATE TABLE demandesreorient (
    id                              SERIAL NOT NULL PRIMARY KEY,
	orientstruct_id					INTEGER DEFAULT NULL REFERENCES orientsstructs(id),
	motifdemreorient_id				INTEGER NOT NULL REFERENCES motifsdemsreorients(id),
	urgent							type_booleannumber NOT NULL DEFAULT '0',
	passageep						type_booleannumber NOT NULL DEFAULT '0',
	-- Ancien
	vx_typeorient					INTEGER NOT NULL REFERENCES typesorients(id),
	vx_structure					INTEGER NOT NULL REFERENCES structuresreferentes(id),
	vx_referent						INTEGER NOT NULL REFERENCES referents(id),
	-- Nouveau
	nv_typeorient					INTEGER NOT NULL REFERENCES typesorients(id),
	nv_structure					INTEGER DEFAULT NULL REFERENCES structuresreferentes(id),
	nv_referent						INTEGER DEFAULT NULL REFERENCES referents(id),
	-- Concertation
	accordconcertation				type_accordconcertation_reorient NOT NULL DEFAULT 'attente',
	dateconcertation				DATE DEFAULT NULL,
	dateecheance					DATE DEFAULT NULL,
	motivation						TEXT DEFAULT NULL,
	-- FIXME: avec les réexamens
	sceanceep_id					INTEGER DEFAULT NULL REFERENCES sceanceseps(id),
	nv_orientstruct_id				INTEGER DEFAULT NULL REFERENCES orientsstructs(id),
	vx_demandesreorient_id			INTEGER DEFAULT NULL REFERENCES demandesreorient(id),
	created							TIMESTAMP WITHOUT TIME ZONE,
	modified						TIMESTAMP WITHOUT TIME ZONE
);

-- champ calculé "modifiable"
-- -> ( ( accordconcertation = 'attente' ) AND ( dateecheance IS NULL OR ( dateecheance <= NOW() ) ) )
/**
*
*/

/**
* Résultats des scéances ep
*/

CREATE TABLE sceanceseps_demandesreorient (
	id								SERIAL NOT NULL PRIMARY KEY,
	sceanceep_id					INTEGER NOT NULL REFERENCES sceanceseps(id),
	demandereorient_id				INTEGER NOT NULL REFERENCES demandesreorient(id)
);

CREATE TABLE decisionsreorient (
    id                              SERIAL NOT NULL PRIMARY KEY,
	demandereorient_id				INTEGER DEFAULT NULL REFERENCES demandesreorient(id),
	etape							type_etapedecisionep NOT NULL DEFAULT 'ep',
	decision						type_decisionep NOT NULL,
	commentaire						TEXT DEFAULT NULL,
	-- Nouveau
	nv_typeorient					INTEGER DEFAULT NULL REFERENCES typesorients(id),
	nv_structure					INTEGER DEFAULT NULL REFERENCES structuresreferentes(id),
	nv_referent						INTEGER DEFAULT NULL REFERENCES referents(id),
	created							TIMESTAMP WITHOUT TIME ZONE,
	modified						TIMESTAMP WITHOUT TIME ZONE
);

-- -----------------------------------------------------------------------------

COMMIT;