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

SELECT add_missing_table_field ( 'public', 'bilansparcours66', 'nvcontratinsertion_id', 'INTEGER' );
SELECT add_missing_constraint ( 'public', 'bilansparcours66', 'bilansparcours66_nvcontratinsertion_id_fkey', 'contratsinsertion', 'nvcontratinsertion_id', false );

CREATE UNIQUE INDEX bilansparcours66_nvcontratinsertion_id_idx ON bilansparcours66(nvcontratinsertion_id);

SELECT public.alter_enumtype ( 'TYPE_PROPOSITIONBILANPARCOURS', ARRAY['audition','parcours','traitement','auditionpe','parcourspe','aucun'] );

-------------------------------------------------------------------------------------------------------------
-- 20121130: Intégration des données DOM manquantes en base
-------------------------------------------------------------------------------------------------------------

--Dans les flux Bénéficiaires : ajouter la table aviscgssdompersonnes
			
DROP TABLE IF EXISTS aviscgssdompersonnes CASCADE;
CREATE TABLE aviscgssdompersonnes (
	id 					SERIAL NOT NULL PRIMARY KEY,
	personne_id        INTEGER NOT NULL REFERENCES personnes(id) ON DELETE CASCADE ON UPDATE CASCADE,
	jusactdom			CHAR(1) NOT NULL,
	resujusactdom		CHAR(1)
);
COMMENT ON TABLE aviscgssdompersonnes IS 'Regroupement des décisions de la CGSS (Caisse Générale Sécurité Sociale) liées à la personne (Mayotte)';

DROP INDEX IF EXISTS aviscgssdompersonnes_personne_id_idx;
CREATE UNIQUE INDEX aviscgssdompersonnes_personne_id_idx ON aviscgssdompersonnes( personne_id );

ALTER TABLE aviscgssdompersonnes ADD CONSTRAINT aviscgssdompersonnes_jusactdom_in_list_chk CHECK ( cakephp_validate_in_list( jusactdom, ARRAY['A', 'D', 'F', 'J', 'M'] ) );
ALTER TABLE aviscgssdompersonnes ADD CONSTRAINT aviscgssdompersonnes_resujusactdom_in_list_chk CHECK ( cakephp_validate_in_list( resujusactdom, ARRAY['N', 'T'] ) );


--- Ajouter les 5 champs suivants à la table detailsdroitsrsa
SELECT add_missing_table_field('public', 'detailsdroitsrsa', 'surfagridom', 'NUMERIC(5,2)' );
SELECT add_missing_table_field('public', 'detailsdroitsrsa', 'ddsurfagridom', 'DATE' );
SELECT add_missing_table_field('public', 'detailsdroitsrsa', 'surfagridompla', 'NUMERIC(5,2)' );
SELECT add_missing_table_field('public', 'detailsdroitsrsa', 'nbtotaidefamsurfdom', 'INTEGER' );
SELECT add_missing_table_field('public', 'detailsdroitsrsa', 'nbtotpersmajosurfdom', 'INTEGER' );

--------------------------------------------------------------------------------
-- Ajout d'une valeur finale pour la déicison du CER Particulier CG66
--------------------------------------------------------------------------------
SELECT add_missing_table_field( 'public', 'proposdecisionscers66', 'decisionfinale', 'TYPE_NO' );

-------------------------------------------------------------------------------------------------------------
-- 20130115 : Ajout du booléen permettant de vérifier qu'un fichier est lié au Foyer (Corbeille PCG CG66)
-------------------------------------------------------------------------------------------------------------

SELECT add_missing_table_field ('public', 'foyers', 'haspiecejointe', 'type_booleannumber');
ALTER TABLE foyers ALTER COLUMN haspiecejointe SET DEFAULT '0'::TYPE_BOOLEANNUMBER;
UPDATE foyers SET haspiecejointe = '0'::TYPE_BOOLEANNUMBER WHERE haspiecejointe IS NULL;
ALTER TABLE foyers ALTER COLUMN haspiecejointe SET NOT NULL;

--------------------------------------------------------------------------------
-- 20130118 : Ajout des fichiers liés au module memos
--------------------------------------------------------------------------------
SELECT add_missing_table_field('public', 'memos', 'haspiecejointe', 'type_booleannumber' );
ALTER TABLE memos ALTER COLUMN haspiecejointe SET DEFAULT '0'::TYPE_BOOLEANNUMBER;
UPDATE memos SET haspiecejointe = '0'::TYPE_BOOLEANNUMBER WHERE haspiecejointe IS NULL;
ALTER TABLE memos ALTER COLUMN haspiecejointe SET NOT NULL;


--------------------------------------------------------------------------------
-- 20121203 : Ajout d'une table manifestationsbilansparcours66 afin de stocker les
-- éléments reseignés par l'allocataire suite à un passage en EPL Audition
--------------------------------------------------------------------------------
DROP TABLE IF EXISTS manifestationsbilansparcours66 CASCADE;
CREATE TABLE manifestationsbilansparcours66 (
	id					SERIAL NOT NULL PRIMARY KEY,
	bilanparcours66_id 	INTEGER NOT NULL REFERENCES bilansparcours66(id) ON DELETE CASCADE ON UPDATE CASCADE,
	commentaire			TEXT NOT NULL,
	datemanifestation	DATE NOT NULL,
	haspiecejointe		TYPE_BOOLEANNUMBER NOT NULL DEFAULT '0',
	created				TIMESTAMP WITHOUT TIME ZONE,
	modified			TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE manifestationsbilansparcours66 IS 'Table pour les manifestations de l''allocataire en lien avec un passage en EPL Audition (CG66)';

DROP INDEX IF EXISTS manifestationsbilansparcours66_bilanparcours66_id_idx;
CREATE INDEX manifestationsbilansparcours66_bilanparcours66_id_idx ON manifestationsbilansparcours66( bilanparcours66_id );


--------------------------------------------------------------------------------
-- 20130118 : Ajout de la date d'envoi du courrier d'un traitement PCG (CG66)
--------------------------------------------------------------------------------
SELECT add_missing_table_field( 'public', 'traitementspcgs66', 'dateenvoicourrier', 'DATE' );

--------------------------------------------------------------------------------
-- 20130123 : Ajout d'une zone de commentaire liée aux pièces jointes d'un dossier PCG (CG66)
--------------------------------------------------------------------------------
SELECT add_missing_table_field( 'public', 'dossierspcgs66', 'commentairepiecejointe', 'TEXT' );

-- *****************************************************************************
COMMIT;
-- *****************************************************************************