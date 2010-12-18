-- *****************************************************************************
-- Améliorations des performances
-- INFO: http://archives.postgresql.org/pgsql-performance/2008-10/msg00029.php
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

DROP INDEX IF EXISTS personnes_upper_nom_idx;
DROP INDEX IF EXISTS personnes_upper_prenom_idx;
DROP INDEX IF EXISTS personnes_upper_nomnai_idx;
/*ALTER TABLE personnes ALTER COLUMN nom SET STATISTICS 100;
ALTER TABLE personnes ALTER COLUMN prenom SET STATISTICS 100;
ALTER TABLE personnes ALTER COLUMN nomnai SET STATISTICS 100;*/
CREATE INDEX personnes_upper_nom_idx ON personnes USING btree (upper(nom::text) varchar_pattern_ops);
CREATE INDEX personnes_upper_prenom_idx ON personnes USING btree (upper(prenom::text) varchar_pattern_ops);
CREATE INDEX personnes_upper_nomnai_idx ON personnes USING btree (upper(nomnai::text) varchar_pattern_ops);

DROP INDEX IF EXISTS dossierscaf_personne_id_idx;
CREATE INDEX dossierscaf_personne_id_idx ON dossierscaf USING btree (personne_id);

DROP INDEX IF EXISTS dsps_personne_id_idx;
CREATE INDEX dsps_personne_id_idx ON dsps USING btree (personne_id);

DROP INDEX IF EXISTS infosfinancieres_dossier_id_idx;
DROP INDEX IF EXISTS infosfinancieres_type_allocation_idx;
DROP INDEX IF EXISTS infosfinancieres_dossier_id_type_allocation_idx;
CREATE INDEX infosfinancieres_dossier_id_idx ON infosfinancieres USING btree (dossier_id);
CREATE INDEX infosfinancieres_type_allocation_idx ON infosfinancieres USING btree (type_allocation);
CREATE INDEX infosfinancieres_dossier_id_type_allocation_idx ON infosfinancieres USING btree (dossier_id,type_allocation);

-- *****************************************************************************
COMMIT;
-- *****************************************************************************

VACUUM FULL personnes;
REINDEX TABLE personnes;

VACUUM FULL dossierscaf;
REINDEX TABLE dossierscaf;

VACUUM FULL dsps;
REINDEX TABLE dsps;

VACUUM FULL infosfinancieres;
REINDEX TABLE infosfinancieres;

-- *****************************************************************************