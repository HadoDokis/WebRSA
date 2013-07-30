
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
ALTER TABLE accompagnementscuis66 RENAME TO oldaccompagnementscuis66;

DROP TABLE IF EXISTS accompagnementscuis66 CASCADE;
CREATE TABLE accompagnementscuis66(
    id                          SERIAL NOT NULL PRIMARY KEY,
    cui_id                      INTEGER NOT NULL REFERENCES cuis(id) ON DELETE CASCADE ON UPDATE CASCADE,
    typeaccompagnementcui66     VARCHAR(20) NOT NULL,
    haspiecejointe              VARCHAR(1) NOT NULL DEFAULT '0',
    user_id                     INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
	----------------------------------------------------------------------------
    created                     TIMESTAMP WITHOUT TIME ZONE,
    modified                    TIMESTAMP WITHOUT TIME ZONE
);

DROP INDEX IF EXISTS accompagnementscuis66_cui_id_idx;
CREATE INDEX accompagnementscuis66_cui_id_idx ON accompagnementscuis66( cui_id );

DROP INDEX IF EXISTS accompagnementscuis66_user_id_idx;
CREATE INDEX accompagnementscuis66_user_id_idx ON accompagnementscuis66( user_id );

SELECT alter_table_drop_constraint_if_exists( 'public', 'accompagnementscuis66', 'accompagnementscuis66_typeaccompagnementcui66_in_list_chk' );
ALTER TABLE accompagnementscuis66 ADD CONSTRAINT accompagnementscuis66_typeaccompagnementcui66_in_list_chk CHECK ( cakephp_validate_in_list( typeaccompagnementcui66, ARRAY['immersion', 'formation', 'bilan'] ) );

SELECT alter_table_drop_constraint_if_exists( 'public', 'accompagnementscuis66', 'accompagnementscuis66_haspiecejointe_in_list_chk' );
ALTER TABLE accompagnementscuis66 ADD CONSTRAINT accompagnementscuis66_haspiecejointe_in_list_chk CHECK ( cakephp_validate_in_list( haspiecejointe, ARRAY['0', '1'] ) );

--------------------------------------------------------------------------------
DROP  TABLE IF EXISTS bilanscuis66 CASCADE;
CREATE TABLE bilanscuis66(
    id                          SERIAL NOT NULL PRIMARY KEY,
    accompagnementcui66_id      INTEGER NOT NULL REFERENCES accompagnementscuis66(id) ON DELETE CASCADE ON UPDATE CASCADE,
    datedebut                   DATE,
    datefin                     DATE,
    observation                 TEXT,
    orgsuivicui66_id              INTEGER NOT NULL REFERENCES structuresreferentes(id) ON DELETE CASCADE ON UPDATE CASCADE,
    refsuivicui66_id              INTEGER NOT NULL REFERENCES referents(id) ON DELETE CASCADE ON UPDATE CASCADE,
    datesignaturebilan          DATE NOT NULL,
	----------------------------------------------------------------------------
    created                     TIMESTAMP WITHOUT TIME ZONE,
    modified                    TIMESTAMP WITHOUT TIME ZONE
);
DROP INDEX IF EXISTS bilanscuis66_orgsuivicui66_id_idx;
CREATE INDEX bilanscuis66_orgsuivicui66_id_idx ON bilanscuis66( orgsuivicui66_id );

DROP INDEX IF EXISTS bilanscuis66_refsuivicui66_id_idx;
CREATE INDEX bilanscuis66_refsuivicui66_id_idx ON bilanscuis66( refsuivicui66_id );
--------------------------------------------------------------------------------

DROP  TABLE IF EXISTS formationscuis66 CASCADE;
CREATE TABLE formationscuis66(
    id                          SERIAL NOT NULL PRIMARY KEY,
    accompagnementcui66_id      INTEGER NOT NULL REFERENCES accompagnementscuis66(id) ON DELETE CASCADE ON UPDATE CASCADE,
	----------------------------------------------------------------------------
    created                     TIMESTAMP WITHOUT TIME ZONE,
    modified                    TIMESTAMP WITHOUT TIME ZONE
);

DROP INDEX IF EXISTS formationscuis66_accompagnementcui66_id_idx;
CREATE INDEX formationscuis66_accompagnementcui66_id_idx ON formationscuis66( accompagnementcui66_id );
--------------------------------------------------------------------------------

DROP  TABLE IF EXISTS periodesimmersioncuis66 CASCADE;
CREATE TABLE periodesimmersioncuis66(
    id                          SERIAL NOT NULL PRIMARY KEY,
    accompagnementcui66_id      INTEGER NOT NULL REFERENCES accompagnementscuis66(id) ON DELETE CASCADE ON UPDATE CASCADE,
    nomentaccueil               VARCHAR(50) NOT NULL,
    numvoieentaccueil           VARCHAR(6) DEFAULT NULL,
    typevoieentaccueil          VARCHAR(4) DEFAULT NULL,
    nomvoieentaccueil           VARCHAR(50) DEFAULT NULL,
    compladrentaccueil          VARCHAR(50) DEFAULT NULL,
    codepostalentaccueil        VARCHAR(5) DEFAULT NULL,
    villeentaccueil             VARCHAR(50) DEFAULT NULL,
    activiteentaccueil          VARCHAR(14) DEFAULT NULL,
    datedebperiode              DATE DEFAULT NULL,
    datefinperiode              DATE DEFAULT NULL,
    nbjourperiode               INTEGER DEFAULT NULL,
    secteuraffectation_id       INTEGER NOT NULL REFERENCES codesromesecteursdsps66(id) ON DELETE CASCADE ON UPDATE CASCADE,
    metieraffectation_id        INTEGER NOT NULL REFERENCES codesromemetiersdsps66(id) ON DELETE CASCADE ON UPDATE CASCADE,
    objectifimmersion           VARCHAR(10) NOT NULL,
    datesignatureimmersion      DATE NOT NULL,
	----------------------------------------------------------------------------
    created                     TIMESTAMP WITHOUT TIME ZONE,
    modified                    TIMESTAMP WITHOUT TIME ZONE
);

DROP INDEX IF EXISTS periodesimmersioncuis66_accompagnementcui66_id_idx;
CREATE INDEX periodesimmersioncuis66_accompagnementcui66_id_idx ON periodesimmersioncuis66( accompagnementcui66_id );

DROP INDEX IF EXISTS periodesimmersioncuis66_secteuraffectation_id_idx;
CREATE INDEX periodesimmersioncuis66_secteuraffectation_id_idx ON periodesimmersioncuis66( secteuraffectation_id );

DROP INDEX IF EXISTS periodesimmersioncuis66_metieraffectation_id_idx;
CREATE INDEX periodesimmersioncuis66_metieraffectation_id_idx ON periodesimmersioncuis66( metieraffectation_id );

SELECT alter_table_drop_constraint_if_exists( 'public', 'periodesimmersioncuis66', 'periodesimmersioncuis66_objectifimmersion_in_list_chk' );
ALTER TABLE periodesimmersioncuis66 ADD CONSTRAINT periodesimmersioncuis66_objectifimmersion_in_list_chk CHECK ( cakephp_validate_in_list( objectifimmersion, ARRAY['acquerir', 'confirmer', 'decouvrir','initier'] ) );

--------------------------------------------------------------------------------



-- *****************************************************************************
COMMIT;
-- *****************************************************************************