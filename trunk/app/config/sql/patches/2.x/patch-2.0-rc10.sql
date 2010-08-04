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

-----------------------------------------------------------------------------
-- 21/07/2010: mise à jour pour la V.30 du flux bénéficiaire
-----------------------------------------------------------------------------

ALTER TABLE situationsdossiersrsa ADD COLUMN motirefursa CHAR(3);

-----------------------------------------------------------------------------

CREATE TABLE controlesadministratifs (
    id              	SERIAL NOT NULL PRIMARY KEY,
    dteffcibcontro  	DATE,
    cibcontro        	CHAR(3),
    cibcontromsa        CHAR(3),
    dtdeteccontro       DATE,
    dtclocontro        	DATE,
    libcibcontro        VARCHAR(45),
    famcibcontro        CHAR(2),
    natcibcontro        CHAR(3),
    commacontro        	CHAR(3),
    typecontro        	CHAR(2),
    typeimpaccontro     CHAR(1),
    mtindursacgcontro	DECIMAL(11,2),
    mtraprsacgcontro    DECIMAL(11,2)
);

ALTER TABLE tiersprestatairesapres ADD COLUMN nometaban VARCHAR(24);

ALTER TABLE users ADD COLUMN sensibilite type_no DEFAULT NULL;
-- *****************************************************************************
COMMIT;
-- *****************************************************************************