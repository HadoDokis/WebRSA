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
SELECT add_missing_table_field ('public', 'traitementspcgs66', 'reversedo', 'TYPE_BOOLEANNUMBER');
ALTER TABLE traitementspcgs66 ALTER COLUMN reversedo SET DEFAULT '0'::TYPE_BOOLEANNUMBER;
UPDATE traitementspcgs66 SET reversedo = '0'::TYPE_BOOLEANNUMBER WHERE reversedo IS NULL;
ALTER TABLE traitementspcgs66 ALTER COLUMN reversedo SET NOT NULL;


SELECT add_missing_table_field ('public', 'bilansparcours66', 'personne_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'bilansparcours66', 'bilansparcours66_personne_id_fkey', 'personnes', 'personne_id');
UPDATE bilansparcours66
	SET personne_id = (
		SELECT orientsstructs.personne_id
			FROM orientsstructs
			WHERE orientsstructs.id = orientstruct_id
	);
ALTER TABLE bilansparcours66 ALTER COLUMN personne_id SET NOT NULL;

-------------------------------------------------------------------------------------------------------------
-- 20120220 : Ajout de la clé primaire decisiondefautinsertionep66 dans le dossier PCGs
--				une fois ce dernier généré par l'avis émis par l'EP
-------------------------------------------------------------------------------------------------------------
SELECT add_missing_table_field ('public', 'dossierspcgs66', 'decisiondefautinsertionep66_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'dossierspcgs66', 'dossierspcgs66_decisiondefautinsertionep66_id_fkey', 'decisionsdefautsinsertionseps66', 'decisiondefautinsertionep66_id');
DROP INDEX IF EXISTS dossierspcgs66_decisiondefautinsertionep66_id_idx;
CREATE UNIQUE INDEX dossierspcgs66_decisiondefautinsertionep66_id_idx ON dossierspcgs66 (decisiondefautinsertionep66_id);

SELECT alter_table_drop_column_if_exists('public', 'questionspcgs66', 'descriptionpdo_id');
SELECT add_missing_table_field ('public', 'questionspcgs66', 'decisionpcg66_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'questionspcgs66', 'questionspcgs66_decisionpcg66_id_fkey', 'decisionspcgs66', 'decisionpcg66_id');

SELECT add_missing_table_field ('public', 'decisionsdossierspcgs66', 'defautinsertion', 'TYPE_DEFAUTINSERTIONPCG66');
SELECT add_missing_table_field ('public', 'decisionsdossierspcgs66', 'recidive', 'TYPE_NO');
SELECT add_missing_table_field ('public', 'decisionsdossierspcgs66', 'phase', 'TYPE_PHASEPCG66');
SELECT add_missing_table_field ('public', 'decisionsdossierspcgs66', 'compofoyerpcg66_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'decisionsdossierspcgs66', 'decisionsdossierspcgs66_compofoyerpcg66_id_fkey', 'composfoyerspcgs66', 'compofoyerpcg66_id');

-------------------------------------------------------------------------------------------------------------
-- 20120222 : Ajout d'une valeur activ/inactif pour les informations paramétrables des dossiers PCGs 66
--			dont on n'aurait plus besoin apr la suite
-------------------------------------------------------------------------------------------------------------
SELECT add_missing_table_field ('public', 'composfoyerspcgs66', 'actif', 'TYPE_NO');
ALTER TABLE composfoyerspcgs66 ALTER COLUMN actif SET DEFAULT 'O';
UPDATE composfoyerspcgs66 SET actif = 'O' WHERE actif IS NULL;
ALTER TABLE composfoyerspcgs66 ALTER COLUMN actif SET NOT NULL;

SELECT add_missing_table_field ('public', 'decisionspcgs66', 'actif', 'TYPE_NO');
ALTER TABLE decisionspcgs66 ALTER COLUMN actif SET DEFAULT 'O';
UPDATE decisionspcgs66 SET actif = 'O' WHERE actif IS NULL;
ALTER TABLE decisionspcgs66 ALTER COLUMN actif SET NOT NULL;

-------------------------------------------------------------------------------------------------------------
-- 20120223 : Ajout d'une clé primaire pointant sur la table des décisions PCGs paramétrabless
-------------------------------------------------------------------------------------------------------------
SELECT add_missing_table_field ('public', 'decisionsdossierspcgs66', 'decisionpcg66_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'decisionsdossierspcgs66', 'decisionsdossierspcgs66_decisionpcg66_id_fkey', 'decisionspcgs66', 'decisionpcg66_id');

-- Correction: les valeurs "suspensiondefaut" et "suspensionnonrespect" étaient inversées avec les traductions.
SELECT public.alter_enumtype ( 'TYPE_DECISIONDEFAUTINSERTIONEP66', ARRAY['suspensionnonrespect','suspensiondefaut','maintien','reorientationprofverssoc','reorientationsocversprof','annule','reporte', 'suspensionnonrespecttmp','suspensiondefauttmp'] );

UPDATE decisionsdefautsinsertionseps66 SET decision = 'suspensionnonrespecttmp' WHERE decision = 'suspensiondefaut';
UPDATE decisionsdefautsinsertionseps66 SET decision = 'suspensiondefauttmp' WHERE decision = 'suspensionnonrespect';
UPDATE decisionsdefautsinsertionseps66 SET decision = 'suspensiondefaut' WHERE decision = 'suspensiondefauttmp';
UPDATE decisionsdefautsinsertionseps66 SET decision = 'suspensionnonrespect' WHERE decision = 'suspensionnonrespecttmp';

SELECT public.alter_enumtype ( 'TYPE_DECISIONDEFAUTINSERTIONEP66', ARRAY['suspensionnonrespect','suspensiondefaut','maintien','reorientationprofverssoc','reorientationsocversprof','annule','reporte'] );

-- Correction: ...
SELECT public.alter_enumtype ( 'TYPE_DEFAUTINSERTIONPCG66', ARRAY['nc_cg','nc_pe','nr_cg','nr_pe','nc_no', 'suspensiondefaut_audition_orientation', 'suspensiondefaut_auditionpe', 'suspensionnonrespect_audition', 'suspensionnonrespect_auditionpe', 'suspensiondefaut_audition_nonorientation'] );

UPDATE questionspcgs66 SET defautinsertion = 'suspensiondefaut_audition_orientation' WHERE defautinsertion = 'nc_cg';
UPDATE questionspcgs66 SET defautinsertion = 'suspensiondefaut_auditionpe' WHERE defautinsertion = 'nc_pe';
UPDATE questionspcgs66 SET defautinsertion = 'suspensionnonrespect_audition' WHERE defautinsertion = 'nr_cg';
UPDATE questionspcgs66 SET defautinsertion = 'suspensionnonrespect_auditionpe' WHERE defautinsertion = 'nr_pe';
UPDATE questionspcgs66 SET defautinsertion = 'suspensiondefaut_audition_nonorientation' WHERE defautinsertion = 'nc_no';

SELECT public.alter_enumtype ( 'TYPE_DEFAUTINSERTIONPCG66', ARRAY['suspensiondefaut_audition_orientation', 'suspensiondefaut_auditionpe', 'suspensionnonrespect_audition', 'suspensionnonrespect_auditionpe', 'suspensiondefaut_audition_nonorientation'] );


ALTER TABLE bilansparcours66 ALTER COLUMN orientstruct_id DROP NOT NULL;

-- *****************************************************************************
COMMIT;
-- *****************************************************************************