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

-- -----------------------------------------------------------------------------
-- inclusiveRange
-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_inclusive_range( p_check float, p_min float, p_max float ) RETURNS boolean AS
$$
	BEGIN
		RETURN p_check IS NULL OR ( p_check >= p_min AND p_check <= p_max  );
	END;
$$
LANGUAGE plpgsql IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_inclusive_range( p_check float, p_min float, p_max float ) IS
	'Comme cakephp_validate_range(), bornes comprises.';

-- -----------------------------------------------------------------------------

SELECT
	( cakephp_validate_inclusive_range( 20, 100, 1 ) = false )
	AND ( cakephp_validate_inclusive_range( 20, 1, 100 ) = true )
	AND ( cakephp_validate_inclusive_range( .5, 1, 100 ) = false )
	AND ( cakephp_validate_inclusive_range( .5, 0, 100 ) = true )
	AND ( cakephp_validate_inclusive_range( -5, -10, 1 ) = true )
	AND ( cakephp_validate_inclusive_range( 10, 0, 10 ) = true )
	AS passed_tests_cakephp_validate_inclusive_range;

-- *****************************************************************************
COMMIT;
-- *****************************************************************************