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

ALTER TABLE tiersprestatairesapres ALTER COLUMN nomtiturib TYPE VARCHAR(100);

-- 20120209: ajout d'un index pour les filtres "Uniquement la dernière demande RSA pour un même allocataire"
DROP INDEX IF EXISTS personnes_nir13_trim_idx;
CREATE INDEX personnes_nir13_trim_idx ON personnes ( SUBSTRING( TRIM( BOTH ' ' FROM nir ) FROM 1 FOR 13 ) );

-- 20120209: remplacement des colones de types TYPE_STATUTDEMRSA et TYPE_FONORGCEDMUT en types CHARACTER
--           pour ne plus avoir de problème avec l'intégration des flux et ajout de contraintes pour blinder
--           la base
CREATE OR REPLACE FUNCTION cakephp_validate_in_list( text, text[] ) RETURNS boolean AS
$$
	SELECT $1 IS NULL OR ( ARRAY[CAST($1 AS TEXT)] <@ CAST($2 AS TEXT[]) );
$$
LANGUAGE sql IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_in_list( text, text[] ) IS
	'@see http://api.cakephp.org/class/validation#method-ValidationinList';

ALTER TABLE dossiers ALTER COLUMN statudemrsa TYPE CHARACTER(1) USING CAST(statudemrsa AS CHARACTER(1));
ALTER TABLE dossiers ALTER COLUMN fonorgcedmut TYPE CHARACTER(3) USING CAST(fonorgcedmut AS CHARACTER(3));
ALTER TABLE dossiers ALTER COLUMN fonorgprenmut TYPE CHARACTER(3) USING CAST(fonorgprenmut AS CHARACTER(3));

ALTER TABLE dossiers ADD CONSTRAINT dossiers_statudemrsa_in_list_chk CHECK ( cakephp_validate_in_list( statudemrsa, ARRAY['N', 'C', 'A', 'M', 'S'] ) );
ALTER TABLE dossiers ADD CONSTRAINT dossiers_fonorgcedmut_in_list_chk CHECK ( cakephp_validate_in_list( fonorgcedmut, ARRAY['CAF', 'MSA', 'OPF'] ) );
ALTER TABLE dossiers ADD CONSTRAINT dossiers_fonorgprenmut_in_list_chk CHECK ( cakephp_validate_in_list( fonorgprenmut, ARRAY['CAF', 'MSA', 'OPF'] ) );

DROP TYPE TYPE_STATUTDEMRSA;
DROP TYPE TYPE_FONORGCEDMUT;

-- *****************************************************************************
COMMIT;
-- *****************************************************************************