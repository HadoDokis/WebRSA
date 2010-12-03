-- *****************************************************************************
-- FIXME:
--     1°) il faudra des ON DELETE SET NULL ... (lorsqu'on supprime des référents,
--         le dossierep ne doit pas être supprimé).
--     2°) Les tables bilanparcours, ... sont à supprimer
--     3°) Trop d'indexes / indexes manquants ?
--     4°) noms de tables trop longs ?
-- *****************************************************************************

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

-- Anciennes tables
DROP TABLE IF EXISTS parcoursdetectes CASCADE;

-- Nouvelles tables
DROP TABLE IF EXISTS nvsrsepsreorient66 CASCADE;
DROP TABLE IF EXISTS nvsrsepsreorientsrs93 CASCADE;
DROP TABLE IF EXISTS saisinesepssignalementsnrscers93 CASCADE;
DROP TABLE IF EXISTS relancesdetectionscontrats93 CASCADE;
DROP TABLE IF EXISTS avissrmreps93 CASCADE;
DROP TABLE IF EXISTS saisineseps66 CASCADE;
DROP TABLE IF EXISTS bilansparcours66 CASCADE;
DROP TABLE IF EXISTS nvsrsepsreorientsrs93 CASCADE;
DROP TABLE IF EXISTS saisinesepsreorientsrs93 CASCADE;
DROP TABLE IF EXISTS saisinesepsbilansparcours66 CASCADE;
DROP TABLE IF EXISTS maintiensreorientseps CASCADE;
DROP TABLE IF EXISTS dossierseps CASCADE;
DROP TABLE IF EXISTS eps_zonesgeographiques CASCADE;
DROP TABLE IF EXISTS membreseps CASCADE;
DROP TABLE IF EXISTS fonctionsmembreseps CASCADE;
DROP TABLE IF EXISTS seanceseps CASCADE;
DROP TABLE IF EXISTS eps CASCADE;
DROP TABLE IF EXISTS regroupementseps CASCADE;
DROP TABLE IF EXISTS motifsreorients CASCADE;
DROP TABLE IF EXISTS saisinesepdspdos66 CASCADE;

DROP TYPE IF EXISTS TYPE_THEMEEP CASCADE;
DROP TYPE IF EXISTS TYPE_DECISIONEP CASCADE;
DROP TYPE IF EXISTS TYPE_ETAPEDECISIONEP CASCADE;
DROP TYPE IF EXISTS TYPE_NIVEAUDECISIONEP CASCADE;
DROP TYPE IF EXISTS TYPE_QUAL CASCADE;
DROP TYPE IF EXISTS TYPE_ETAPEDOSSIEREP CASCADE;
DROP TYPE IF EXISTS TYPE_TYPEREORIENTATION66 CASCADE;
DROP TYPE IF EXISTS TYPE_ETAPERELANCECONTENTIEUSE CASCADE;
DROP TYPE IF EXISTS TYPE_TYPERELANCECONTENTIEUSE CASCADE;
DROP TYPE IF EXISTS type_orgpayeur CASCADE;
DROP TYPE IF EXISTS type_dateactive CASCADE;

-- INFO: http://archives.postgresql.org/pgsql-sql/2005-09/msg00266.php
CREATE OR REPLACE FUNCTION public.add_missing_table_field (text, text, text, text)
returns bool as '
DECLARE
  p_namespace alias for $1;
  p_table     alias for $2;
  p_field     alias for $3;
  p_type      alias for $4;
  v_row       record;
  v_query     text;
BEGIN
  select 1 into v_row from pg_namespace n, pg_class c, pg_attribute a
     where
         --public.slon_quote_brute(n.nspname) = p_namespace and
         n.nspname = p_namespace and
         c.relnamespace = n.oid and
         --public.slon_quote_brute(c.relname) = p_table and
         c.relname = p_table and
         a.attrelid = c.oid and
         --public.slon_quote_brute(a.attname) = p_field;
         a.attname = p_field;
  if not found then
    raise notice ''Upgrade table %.% - add field %'', p_namespace, p_table, p_field;
    v_query := ''alter table '' || p_namespace || ''.'' || p_table || '' add column '';
    v_query := v_query || p_field || '' '' || p_type || '';'';
    execute v_query;
    return ''t'';
  else
    return ''f'';
  end if;
END;' language plpgsql;

COMMENT ON FUNCTION public.add_missing_table_field (text, text, text, text)
IS 'Add a column of a given type to a table if it is missing';

-- -----------------------------------------------------------------------------

CREATE TABLE regroupementseps (
	id      SERIAL NOT NULL PRIMARY KEY,
	name	VARCHAR(255) NOT NULL
);

CREATE UNIQUE INDEX regroupementseps_name_idx ON regroupementseps(name);
ALTER TABLE regroupementseps OWNER TO webrsa;
-- -----------------------------------------------------------------------------

CREATE TYPE TYPE_NIVEAUDECISIONEP AS ENUM ( 'nontraite', 'ep', 'cg' );

CREATE TABLE eps (
	id      					SERIAL NOT NULL PRIMARY KEY,
	name						VARCHAR(255) NOT NULL,
	regroupementep_id			INTEGER NOT NULL REFERENCES regroupementseps(id) ON DELETE CASCADE ON UPDATE CASCADE,
	saisineepreorientsr93		TYPE_NIVEAUDECISIONEP NOT NULL DEFAULT 'nontraite',
	saisineepbilanparcours66	TYPE_NIVEAUDECISIONEP NOT NULL DEFAULT 'nontraite',
	saisineepdpdo66				TYPE_NIVEAUDECISIONEP NOT NULL DEFAULT 'nontraite'
);

CREATE UNIQUE INDEX eps_name_idx ON eps(name);
CREATE INDEX eps_regroupementep_id_idx ON eps(regroupementep_id);
ALTER TABLE eps OWNER TO webrsa;
-- -----------------------------------------------------------------------------

CREATE TABLE fonctionsmembreseps (
	id      SERIAL NOT NULL PRIMARY KEY,
	name	VARCHAR(255) NOT NULL
);

CREATE UNIQUE INDEX fonctionsmembreseps_name_idx ON fonctionsmembreseps(name);
ALTER TABLE fonctionsmembreseps OWNER TO webrsa;
-- -----------------------------------------------------------------------------

CREATE TYPE TYPE_QUAL AS ENUM ( 'M.', 'Mlle.', 'Mme.' );

CREATE TABLE membreseps (
	id      			SERIAL NOT NULL PRIMARY KEY,
	ep_id				INTEGER NOT NULL REFERENCES eps(id) ON DELETE CASCADE ON UPDATE CASCADE,
	fonctionmembreep_id	INTEGER NOT NULL REFERENCES fonctionsmembreseps(id) ON DELETE CASCADE ON UPDATE CASCADE,
	qual				TYPE_QUAL NOT NULL,
	nom					VARCHAR(255) NOT NULL,
	prenom				VARCHAR(255) NOT NULL
	-- TODO: adresse, tél, etc ...
);

CREATE INDEX membreseps_ep_id_idx ON membreseps(ep_id);
CREATE INDEX membreseps_fonctionmembreep_id_idx ON membreseps(fonctionmembreep_id);
ALTER TABLE membreseps OWNER TO webrsa;
-- -----------------------------------------------------------------------------

CREATE TABLE eps_zonesgeographiques (
	id      			SERIAL NOT NULL PRIMARY KEY,
	ep_id				INTEGER NOT NULL REFERENCES eps(id) ON DELETE CASCADE ON UPDATE CASCADE,
	zonegeographique_id	INTEGER NOT NULL REFERENCES zonesgeographiques(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE UNIQUE INDEX eps_zonesgeographiques_unique_idx ON eps_zonesgeographiques(ep_id,zonegeographique_id);
CREATE INDEX eps_zonesgeographiques_ep_id_idx ON eps_zonesgeographiques(ep_id);
CREATE INDEX eps_zonesgeographiques_zonegeographique_id_idx ON eps_zonesgeographiques(zonegeographique_id);
ALTER TABLE eps_zonesgeographiques OWNER TO webrsa;
-- -----------------------------------------------------------------------------

CREATE TYPE TYPE_ETAPEDECISIONEP AS ENUM ( 'ep', 'cg' );

CREATE TABLE seanceseps (
	id      				SERIAL NOT NULL PRIMARY KEY,
	ep_id					INTEGER NOT NULL REFERENCES eps(id) ON DELETE CASCADE ON UPDATE CASCADE,
	structurereferente_id	INTEGER REFERENCES structuresreferentes(id) ON DELETE CASCADE ON UPDATE CASCADE,
	dateseance				TIMESTAMP WITHOUT TIME ZONE NOT NULL,
	finalisee				TYPE_ETAPEDECISIONEP DEFAULT NULL
);

CREATE INDEX seanceseps_ep_id_idx ON seanceseps(ep_id);
CREATE INDEX seanceseps_structurereferente_id_idx ON seanceseps(structurereferente_id);
ALTER TABLE seanceseps OWNER TO webrsa;

-- -----------------------------------------------------------------------------

CREATE TYPE TYPE_THEMEEP AS ENUM ( 'saisinesepsreorientsrs93', 'saisinesepsbilansparcours66', 'suspensionsreductionsallocations93', 'saisinesepdspdos66' );
CREATE TYPE TYPE_ETAPEDOSSIEREP AS ENUM ( 'cree', '...', 'seance', 'decisionep', 'decisioncg', 'traite' );

CREATE TABLE dossierseps (
	id      			SERIAL NOT NULL PRIMARY KEY,
	personne_id			INTEGER NOT NULL REFERENCES personnes(id) ON DELETE CASCADE ON UPDATE CASCADE,
	seanceep_id			INTEGER DEFAULT NULL REFERENCES seanceseps(id) ON DELETE SET NULL ON UPDATE CASCADE,
	etapedossierep		TYPE_ETAPEDOSSIEREP NOT NULL DEFAULT 'cree',
	themeep				TYPE_THEMEEP NOT NULL,
	-- urgent ?
	created				TIMESTAMP WITHOUT TIME ZONE,
	modified			TIMESTAMP WITHOUT TIME ZONE
);

COMMENT ON TABLE dossierseps IS 'Dossiers de passage en commission d''EPs';

CREATE INDEX dossierseps_personne_id_idx ON dossierseps(personne_id);
CREATE INDEX dossierseps_ep_id_idx ON dossierseps(seanceep_id);
CREATE INDEX dossierseps_etapedossierep_idx ON dossierseps(etapedossierep);
ALTER TABLE dossierseps OWNER TO webrsa;

-- =============================================================================

CREATE TABLE motifsreorients (
	id      SERIAL NOT NULL PRIMARY KEY,
	name	VARCHAR(255) NOT NULL
);

CREATE UNIQUE INDEX motifsreorients_name_idx ON motifsreorients(name);
ALTER TABLE motifsreorients OWNER TO webrsa;

-- -----------------------------------------------------------------------------

CREATE TABLE saisinesepsreorientsrs93 (
	id      				SERIAL NOT NULL PRIMARY KEY,
	dossierep_id			INTEGER DEFAULT NULL REFERENCES dossierseps(id) ON DELETE CASCADE ON UPDATE CASCADE,
	orientstruct_id			INTEGER NOT NULL REFERENCES orientsstructs(id) ON DELETE CASCADE ON UPDATE CASCADE,
	typeorient_id			INTEGER DEFAULT NULL REFERENCES typesorients(id) ON DELETE SET NULL ON UPDATE CASCADE,
	structurereferente_id	INTEGER DEFAULT NULL REFERENCES structuresreferentes(id) ON DELETE SET NULL ON UPDATE CASCADE,
	motifreorient_id		INTEGER NOT NULL REFERENCES motifsreorients(id) ON DELETE SET NULL ON UPDATE CASCADE,
	commentaire				TEXT DEFAULT NULL,
	accordaccueil			TYPE_BOOLEANNUMBER NOT NULL DEFAULT '0',
	desaccordaccueil		TEXT DEFAULT NULL,
	accordallocataire		TYPE_BOOLEANNUMBER NOT NULL DEFAULT '0',
	urgent					TYPE_BOOLEANNUMBER NOT NULL DEFAULT '0',
	created					TIMESTAMP WITHOUT TIME ZONE,
	modified				TIMESTAMP WITHOUT TIME ZONE
);

COMMENT ON TABLE saisinesepsreorientsrs93 IS 'Saisines d''EPs créées par les structures référentes (CG93)';
ALTER TABLE saisinesepsreorientsrs93 OWNER TO webrsa;

-- -----------------------------------------------------------------------------

CREATE TYPE TYPE_DECISIONEP AS ENUM ( 'accepte', 'refuse' );

CREATE TABLE nvsrsepsreorientsrs93 (
	id      					SERIAL NOT NULL PRIMARY KEY,
	saisineepreorientsr93_id	INTEGER NOT NULL REFERENCES saisinesepsreorientsrs93(id) ON DELETE CASCADE ON UPDATE CASCADE,
	etape						TYPE_ETAPEDECISIONEP NOT NULL,
	decision					TYPE_DECISIONEP NOT NULL,
	typeorient_id				INTEGER DEFAULT NULL REFERENCES typesorients(id) ON DELETE SET NULL ON UPDATE CASCADE,
	structurereferente_id		INTEGER DEFAULT NULL REFERENCES structuresreferentes(id) ON DELETE SET NULL ON UPDATE CASCADE,
	commentaire					TEXT DEFAULT NULL,
	created						TIMESTAMP WITHOUT TIME ZONE,
	modified					TIMESTAMP WITHOUT TIME ZONE
);

COMMENT ON TABLE nvsrsepsreorientsrs93 IS 'Décisions des nouvelles structures referentes concernant les saisines d''EPs créées par les structures référentes (CG93)';
ALTER TABLE nvsrsepsreorientsrs93 OWNER TO webrsa;

-- =============================================================================

CREATE TABLE bilansparcours66 (
	id      				SERIAL NOT NULL PRIMARY KEY,
	referent_id				INTEGER NOT NULL REFERENCES referents(id) ON DELETE CASCADE ON UPDATE CASCADE,
	orientstruct_id			INTEGER NOT NULL REFERENCES orientsstructs(id) ON DELETE CASCADE ON UPDATE CASCADE,
	contratinsertion_id		INTEGER NOT NULL REFERENCES contratsinsertion(id) ON DELETE CASCADE ON UPDATE CASCADE,
	presenceallocataire		TYPE_BOOLEANNUMBER NOT NULL DEFAULT '0',
	situationallocataire	TEXT NOT NULL,
	bilanparcours			TEXT NOT NULL, -- Plus précis ? (diviser en sous-questions)
	infoscomplementaires	TEXT DEFAULT NULL, -- bp "normal"
	preconisationpe			TEXT DEFAULT NULL, -- bp "PE"
	observationsallocataire	TEXT DEFAULT NULL,
	saisineepparcours		TYPE_BOOLEANNUMBER NOT NULL DEFAULT '0',
	maintienorientation		TYPE_BOOLEANNUMBER NOT NULL DEFAULT '0',
	changereferent			TYPE_BOOLEANNUMBER NOT NULL DEFAULT '0',
	ddreconductoncontrat	DATE DEFAULT NULL,
	dfreconductoncontrat	DATE DEFAULT NULL,
	duree_engag				INTEGER DEFAULT NULL,
	created					TIMESTAMP WITHOUT TIME ZONE,
	modified				TIMESTAMP WITHOUT TIME ZONE
);
ALTER TABLE bilansparcours66 OWNER TO webrsa;

-- -----------------------------------------------------------------------------

CREATE TABLE saisinesepsbilansparcours66 (
	id      				SERIAL NOT NULL PRIMARY KEY,
	bilanparcours66_id		INTEGER NOT NULL REFERENCES bilansparcours66(id) ON DELETE CASCADE ON UPDATE CASCADE,
	dossierep_id			INTEGER DEFAULT NULL REFERENCES dossierseps(id) ON DELETE CASCADE ON UPDATE CASCADE,
	typeorient_id			INTEGER DEFAULT NULL REFERENCES typesorients(id) ON DELETE SET NULL ON UPDATE CASCADE,
	structurereferente_id	INTEGER DEFAULT NULL REFERENCES structuresreferentes(id) ON DELETE SET NULL ON UPDATE CASCADE,
	/*motifreorient_id		INTEGER NOT NULL REFERENCES motifsreorients(id) ON DELETE SET NULL ON UPDATE CASCADE,
	commentaire				TEXT DEFAULT NULL,
	accordaccueil			TYPE_BOOLEANNUMBER NOT NULL DEFAULT '0',
	desaccordaccueil		TEXT DEFAULT NULL,
	accordallocataire		TYPE_BOOLEANNUMBER NOT NULL DEFAULT '0',
	urgent					TYPE_BOOLEANNUMBER NOT NULL DEFAULT '0',*/
	created					TIMESTAMP WITHOUT TIME ZONE,
	modified				TIMESTAMP WITHOUT TIME ZONE
);

COMMENT ON TABLE saisinesepsbilansparcours66 IS 'Saisines d''EPs créées lors du bilan de parcours (CG66)';
ALTER TABLE saisinesepsbilansparcours66 OWNER TO webrsa;

-- -----------------------------------------------------------------------------

CREATE TABLE nvsrsepsreorient66 (
	id      					SERIAL NOT NULL PRIMARY KEY,
	saisineepbilanparcours66_id	INTEGER NOT NULL REFERENCES saisinesepsbilansparcours66(id) ON DELETE CASCADE ON UPDATE CASCADE,
	etape						TYPE_ETAPEDECISIONEP NOT NULL,
	decision					TYPE_DECISIONEP,
	typeorient_id				INTEGER DEFAULT NULL REFERENCES typesorients(id) ON DELETE SET NULL ON UPDATE CASCADE,
	structurereferente_id		INTEGER DEFAULT NULL REFERENCES structuresreferentes(id) ON DELETE SET NULL ON UPDATE CASCADE,
	commentaire					TEXT DEFAULT NULL,
	created						TIMESTAMP WITHOUT TIME ZONE,
	modified					TIMESTAMP WITHOUT TIME ZONE
);

COMMENT ON TABLE nvsrsepsreorient66 IS 'Décisions des nouvelles structures referentes concernant les saisines d''EPs suite au bilan de parcours (CG66)';
ALTER TABLE nvsrsepsreorient66 OWNER TO webrsa;

-- =============================================================================

CREATE TYPE TYPE_ETAPERELANCECONTENTIEUSE AS ENUM ( 'relance1', 'relance2', 'relancecontentieuse1', 'relancecontentieuse2' );
CREATE TYPE TYPE_TYPERELANCECONTENTIEUSE AS ENUM ( 'absence', 'echeance' ); -- relance pour absence de contrat ou non renouvellement d'un contrat arrivé à échéance

CREATE TABLE relancesdetectionscontrats93 (
	id      			SERIAL NOT NULL PRIMARY KEY,
	personne_id			INTEGER NOT NULL REFERENCES personnes(id) ON DELETE CASCADE ON UPDATE CASCADE,
	orientstruct_id		INTEGER NOT NULL REFERENCES orientsstructs(id) ON DELETE CASCADE ON UPDATE CASCADE,
	contratinsertion_id	INTEGER DEFAULT NULL REFERENCES contratsinsertion(id) ON DELETE CASCADE ON UPDATE CASCADE,
	etaperelance		TYPE_ETAPERELANCECONTENTIEUSE NOT NULL,
	typerelance			TYPE_TYPERELANCECONTENTIEUSE NOT NULL,
	-- Indique si l'allocataire est sorti de la procédure de relance à cette étape
	sortieprocedure		TYPE_BOOLEANNUMBER NOT NULL DEFAULT '0',
	-- courrier_id ...
	-- dossier d'EP qui sera crée lors de la relance contentieuse 2
	dossierep_id		INTEGER DEFAULT NULL REFERENCES dossierseps(id) ON DELETE SET NULL ON UPDATE CASCADE,
	created				TIMESTAMP WITHOUT TIME ZONE,
	modified			TIMESTAMP WITHOUT TIME ZONE
);

COMMENT ON TABLE relancesdetectionscontrats93 IS 'Relances liées à la procédure de détection de suspension et réduction d''allocations alimentant les EPs (CG93)';

-- TODO: ajouter un check afin que dossierep_id puisse ne pas être NULL SSI le tuple représente l'étape relancecontentieuse2 ?
-- TODO: ajouter un champ vers le nouveau contrat lorsqu'on sort de la procédure ?
-- TODO: ajouter une colonne pour dire que le tuble doit encore être traité à cette étape ?

-- -----------------------------------------------------------------------------

CREATE TABLE saisinesepssignalementsnrscers93 (
	id      				SERIAL NOT NULL PRIMARY KEY,
	dossierep_id			INTEGER DEFAULT NULL REFERENCES dossierseps(id) ON DELETE CASCADE ON UPDATE CASCADE,
	orientstruct_id			INTEGER NOT NULL REFERENCES orientsstructs(id) ON DELETE CASCADE ON UPDATE CASCADE,
	contratinsertion_id		INTEGER NOT NULL REFERENCES contratsinsertion(id) ON DELETE CASCADE ON UPDATE CASCADE,
	obligationsnonremplies	TEXT NOT NULL,
	created					TIMESTAMP WITHOUT TIME ZONE,
	modified				TIMESTAMP WITHOUT TIME ZONE
);

COMMENT ON TABLE saisinesepssignalementsnrscers93 IS 'Saisines d''EPs de signalement pour non respect du contrat d''engagement réciproque (CG93)';

-- =============================================================================

/*CREATE TYPE TYPE_TYPEREORIENTATION66 AS ENUM ( 'social_pro', 'pro_social' ); -- FIXME: autres valeurs

CREATE TABLE maintiensreorientseps (
	id      				SERIAL NOT NULL PRIMARY KEY,
	dossierep_id			INTEGER NOT NULL REFERENCES dossierseps(id) ON DELETE CASCADE ON UPDATE CASCADE,
	contratinsertion_id		INTEGER DEFAULT NULL REFERENCES contratsinsertion(id) ON DELETE CASCADE ON UPDATE CASCADE,
	cui_id					INTEGER DEFAULT NULL REFERENCES cuis(id) ON DELETE CASCADE ON UPDATE CASCADE,
	orientstruct_id			INTEGER DEFAULT NULL REFERENCES orientsstructs(id) ON DELETE CASCADE ON UPDATE CASCADE
);

COMMENT ON TABLE maintiensreorientseps IS 'Partie "Maintien ou réorientation de parcours" du dossier EP d''un allocataire';

CREATE INDEX maintiensreorientseps_dossierep_id_idx ON maintiensreorientseps(dossierep_id);
CREATE INDEX maintiensreorientseps_contratinsertion_id_idx ON maintiensreorientseps(contratinsertion_id);
CREATE INDEX maintiensreorientseps_cui_id_idx ON maintiensreorientseps(cui_id);
CREATE INDEX maintiensreorientseps_orientstruct_id_idx ON maintiensreorientseps(orientstruct_id);

-- -----------------------------------------------------------------------------

CREATE TABLE bilansparcours66 (
	id      				SERIAL NOT NULL PRIMARY KEY,
	referent_id				INTEGER NOT NULL REFERENCES referents(id) ON DELETE CASCADE ON UPDATE CASCADE,
	contratinsertion_id		INTEGER DEFAULT NULL REFERENCES contratsinsertion(id) ON DELETE CASCADE ON UPDATE CASCADE,
	cui_id					INTEGER DEFAULT NULL REFERENCES cuis(id) ON DELETE CASCADE ON UPDATE CASCADE,
	presenceallocataire		TYPE_BOOLEANNUMBER NOT NULL DEFAULT '0',
	situationallocataire	TEXT NOT NULL,
	bilanparcours			TEXT NOT NULL, -- Plus précis ? (diviser en sous-questions)
	infoscomplementaires	TEXT DEFAULT NULL, -- bp "normal"
	preconisationpe			TEXT DEFAULT NULL, -- bp "PE"
	observationsallocataire	TEXT DEFAULT NULL,
	saisineepparcours		TYPE_BOOLEANNUMBER NOT NULL DEFAULT '0',
	maintienorientation		TYPE_BOOLEANNUMBER NOT NULL DEFAULT '0',
	changereferent			TYPE_BOOLEANNUMBER NOT NULL DEFAULT '0',
	ddreconductoncontrat	DATE DEFAULT NULL,
	dfreconductoncontrat	DATE DEFAULT NULL
);

COMMENT ON TABLE bilansparcours66 IS 'Bilan de parcours pour le CG 66';

CREATE INDEX bilansparcours66_referent_id_idx ON bilansparcours66(referent_id);
CREATE INDEX bilansparcours66_contratinsertion_id_idx ON bilansparcours66(contratinsertion_id);
CREATE INDEX bilansparcours66_cui_id_idx ON bilansparcours66(cui_id);

-- -----------------------------------------------------------------------------

CREATE TABLE saisineseps66 (
	id      				SERIAL NOT NULL PRIMARY KEY,
	bilanparcours66_id		INTEGER NOT NULL REFERENCES bilansparcours66(id) ON DELETE CASCADE ON UPDATE CASCADE,
	maintienreorientep_id	INTEGER DEFAULT NULL REFERENCES maintiensreorientseps(id) ON DELETE CASCADE ON UPDATE CASCADE,
	maintienorientation		TYPE_BOOLEANNUMBER NOT NULL,
	changereferent			TYPE_BOOLEANNUMBER NOT NULL DEFAULT '0',
	ddreconductoncontrat	DATE DEFAULT NULL,
	dfreconductoncontrat	DATE DEFAULT NULL,
	typereorientation		TYPE_TYPEREORIENTATION66 DEFAULT NULL,
	commentaire				TEXT DEFAULT NULL
);

COMMENT ON TABLE saisineseps66 IS 'Fiche de saisine des EPs pour le CG 66';

-- -----------------------------------------------------------------------------

CREATE TABLE avissrmreps93 (
	id      				SERIAL NOT NULL PRIMARY KEY,
	maintienreorientep_id	INTEGER NOT NULL REFERENCES maintiensreorientseps(id) ON DELETE CASCADE ON UPDATE CASCADE,
	dttransmission			DATE DEFAULT NULL,
	dtlimite				DATE DEFAULT NULL,
	dtavissr				DATE DEFAULT NULL,
	avissr					TYPE_BOOLEANNUMBER NOT NULL DEFAULT '0',
	signalesr				TYPE_BOOLEANNUMBER NOT NULL DEFAULT '0'
);

COMMENT ON TABLE avissrmreps93 IS 'Signalement (avis) des structures référentes pour le thème "Maintien ou réorientation de parcours" des EPs pour le CG 93';

CREATE UNIQUE INDEX avissrmreps93_maintienreorientep_id_idx ON avissrmreps93(maintienreorientep_id);
CREATE INDEX avissrmreps93_avissr_idx ON avissrmreps93(avissr);
CREATE INDEX avissrmreps93_signalesr_idx ON avissrmreps93(signalesr);*/

-- *****************************************************************************

SELECT add_missing_table_field ('public', 'propospdos', 'serviceinstructeur_id', 'integer');
ALTER TABLE propospdos ALTER COLUMN ADD FOREIGN KEY (serviceinstructeur_id) REFERENCES servicesinstructeurs (id);

SELECT add_missing_table_field ('public', 'propospdos', 'created', 'TIMESTAMP WITHOUT TIME ZONE');
SELECT add_missing_table_field ('public', 'propospdos', 'modified', 'TIMESTAMP WITHOUT TIME ZONE');

CREATE TYPE type_orgpayeur AS ENUM ( 'CAF', 'MSA' );
SELECT add_missing_table_field ('public', 'propospdos', 'orgpayeur', 'type_orgpayeur');

CREATE TYPE type_dateactive AS ENUM ( 'datedepart', 'datereception' );
SELECT add_missing_table_field ('public', 'descriptionspdos', 'dateactive', 'type_dateactive');
ALTER TABLE descriptionspdos ALTER COLUMN dateactive SET NOT NULL;
ALTER TABLE descriptionspdos ALTER COLUMN dateactive SET DEFAULT 'datedepart';
SELECT add_missing_table_field ('public', 'descriptionspdos', 'declencheep', 'type_booleannumber');
ALTER TABLE descriptionspdos ALTER COLUMN declencheep SET NOT NULL;
ALTER TABLE descriptionspdos ALTER COLUMN declencheep SET DEFAULT '0';

SELECT add_missing_table_field ('public', 'traitementspdos', 'dateecheance', 'DATE');
SELECT add_missing_table_field ('public', 'traitementspdos', 'daterevision', 'DATE');
SELECT add_missing_table_field ('public', 'traitementspdos', 'personne_id', 'integer');
ALTER TABLE propospdos ALTER COLUMN ADD FOREIGN KEY (personne_id) REFERENCES personnes (id);
SELECT add_missing_table_field ('public', 'traitementspdos', 'ficheanalyse', 'TEXT');
SELECT add_missing_table_field ('public', 'descriptionspdos', 'clos', 'INTEGER');
ALTER TABLE descriptionspdos ALTER COLUMN declencheep SET NOT NULL;
ALTER TABLE descriptionspdos ALTER COLUMN declencheep SET DEFAULT 0;

CREATE TABLE saisinesepdspdos66 (
	id      				SERIAL NOT NULL PRIMARY KEY,
	dossierep_id			INTEGER DEFAULT NULL REFERENCES dossierseps(id) ON DELETE CASCADE ON UPDATE CASCADE,
	traitementpdo_id		INTEGER NOT NULL REFERENCES traitementspdos (id),
	created					TIMESTAMP WITHOUT TIME ZONE,
	modified				TIMESTAMP WITHOUT TIME ZONE
);

CREATE TABLE nvsepdspdos66 (
	id      				SERIAL NOT NULL PRIMARY KEY,
	saisineepdpdo66_id		INTEGER NOT NULL REFERENCES saisinesepdspdos66(id) ON DELETE CASCADE ON UPDATE CASCADE,
	etape					TYPE_ETAPEDECISIONEP NOT NULL,
	decisionpdo_id			INTEGER REFERENCES decisionspdos (id),
	commentaire				TEXT DEFAULT NULL,
	nonadmis				type_nonadmis,
	motifpdo				VARCHAR(1),
	datedecisionpdo			DATE,
	created					TIMESTAMP WITHOUT TIME ZONE,
	modified				TIMESTAMP WITHOUT TIME ZONE
);

-- *****************************************************************************
-- Modification pour reprendre l'ancien bilan de parcours du 66
-- *****************************************************************************

SELECT add_missing_table_field ('public', 'bilansparcours66', 'accordprojet', 'type_booleannumber');
SELECT add_missing_table_field ('public', 'bilansparcours66', 'maintienorientsansep', 'type_orient');
SELECT add_missing_table_field ('public', 'bilansparcours66', 'choixparcours', 'type_choixparcours');
SELECT add_missing_table_field ('public', 'bilansparcours66', 'changementrefsansep', 'type_no');
SELECT add_missing_table_field ('public', 'bilansparcours66', 'maintienorientparcours', 'type_orient');
SELECT add_missing_table_field ('public', 'bilansparcours66', 'changementrefparcours', 'type_no');
SELECT add_missing_table_field ('public', 'bilansparcours66', 'reorientation', 'type_reorientation');
SELECT add_missing_table_field ('public', 'bilansparcours66', 'examenaudition', 'type_type_demande');
SELECT add_missing_table_field ('public', 'bilansparcours66', 'maintienorientavisep', 'type_orient');
SELECT add_missing_table_field ('public', 'bilansparcours66', 'changementrefeplocale', 'type_no');
SELECT add_missing_table_field ('public', 'bilansparcours66', 'reorientationeplocale', 'type_reorientation');
SELECT add_missing_table_field ('public', 'bilansparcours66', 'typeeplocale', 'type_typeeplocale');
SELECT add_missing_table_field ('public', 'bilansparcours66', 'decisioncommission', 'type_aviscommission');
SELECT add_missing_table_field ('public', 'bilansparcours66', 'decisioncoordonnateur', 'type_aviscoordonnateur');
SELECT add_missing_table_field ('public', 'bilansparcours66', 'decisioncga', 'type_aviscoordonnateur');

SELECT add_missing_table_field ('public', 'bilansparcours66', 'datebilan', 'DATE');
SELECT add_missing_table_field ('public', 'bilansparcours66', 'observbenef', 'TEXT');
SELECT add_missing_table_field ('public', 'bilansparcours66', 'objinit', 'TEXT');
SELECT add_missing_table_field ('public', 'bilansparcours66', 'objatteint', 'TEXT');
SELECT add_missing_table_field ('public', 'bilansparcours66', 'objnew', 'TEXT');
SELECT add_missing_table_field ('public', 'bilansparcours66', 'motifsaisine', 'TEXT');

ALTER TABLE bilansparcours66 ALTER COLUMN situationallocataire DROP NOT NULL;
ALTER TABLE bilansparcours66 ALTER COLUMN bilanparcours DROP NOT NULL;

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
