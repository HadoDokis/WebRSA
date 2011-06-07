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
SELECT add_missing_table_field ('public', 'membreseps', 'numvoie', 'VARCHAR(6)');
SELECT add_missing_table_field ('public', 'membreseps', 'typevoie', 'VARCHAR(4)');
SELECT add_missing_table_field ('public', 'membreseps', 'nomvoie', 'VARCHAR(100)');
SELECT add_missing_table_field ('public', 'membreseps', 'compladr', 'VARCHAR(100)');
SELECT add_missing_table_field ('public', 'membreseps', 'codepostal', 'CHAR(5)');
SELECT add_missing_table_field ('public', 'membreseps', 'ville', 'VARCHAR(100)');

ALTER TABLE actionscandidats ALTER COLUMN contractualisation SET NOT NULL;

SELECT add_missing_table_field ('public', 'proposorientationscovs58', 'decisioncov', 'CHAR(10)');
SELECT add_missing_table_field ('public', 'proposcontratsinsertioncovs58', 'decisioncov', 'VARCHAR(10)');

UPDATE contratsinsertion 
    SET decision_ci = 'N'
    WHERE decision_ci IN ( 'A', 'R' );

UPDATE contratsinsertion 
    SET datevalidation_ci = null
    WHERE decision_ci IN ( 'N', 'E' );

UPDATE contratsinsertion 
    SET datevalidation_ci = dd_ci
    WHERE
        decision_ci = 'V'
        AND datevalidation_ci IS NULL;


ALTER TABLE contratsinsertion ADD CONSTRAINT contratsinsertion_decision_ci_datevalidation_ci_check CHECK(
    ( decision_ci = 'V' AND datevalidation_ci IS NOT NULL )
    OR ( decision_ci <> 'V' AND datevalidation_ci IS NULL )
);


SELECT add_missing_table_field ('public', 'proposorientationscovs58', 'referent_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'proposorientationscovs58', 'proposorientationscovs58_referent_id_fkey', 'referents', 'referent_id');
SELECT add_missing_table_field ('public', 'proposorientationscovs58', 'covreferent_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'proposorientationscovs58', 'proposorientationscovs58_covreferent_id_fkey', 'referents', 'covreferent_id');

SELECT add_missing_table_field ('public', 'eps_membreseps', 'suppleant_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'eps_membreseps', 'eps_membreseps_suppleant_id_fkey', 'referents', 'suppleant_id');

SELECT add_missing_table_field ('public', 'membreseps', 'suppleant_id', 'INTEGER');
ALTER TABLE membreseps DROP COLUMN suppleant_id;
SELECT add_missing_table_field ('public', 'eps_membreseps', 'suppleant_id', 'INTEGER');
ALTER TABLE eps_membreseps DROP COLUMN suppleant_id;



-- *****************************************************************************
-- 20110517, ajout d'une valeur pour le type enum contractualisation dans
-- les fiches de candidatures 66
-- *****************************************************************************
ALTER TABLE actionscandidats ALTER COLUMN contractualisation TYPE TEXT;
DROP TYPE TYPE_CONTRACTUALISATION;
CREATE TYPE TYPE_CONTRACTUALISATION AS ENUM ( 'marche', 'subvention', 'internecg' );
ALTER TABLE actionscandidats ALTER COLUMN contractualisation TYPE TYPE_CONTRACTUALISATION USING CAST(contractualisation AS TYPE_CONTRACTUALISATION);


DROP TYPE IF EXISTS TYPE_POSITIONFICHE;
CREATE TYPE TYPE_POSITIONFICHE AS ENUM ( 'enattente', 'encours', 'nonretenue', 'sortie' );
ALTER TABLE actionscandidats_personnes ADD COLUMN positionfiche TYPE_POSITIONFICHE DEFAULT  'enattente'::type_positionfiche;

SELECT add_missing_table_field ('public', 'actionscandidats_personnes', 'issortie', 'type_booleannumber');

-- *****************************************************************************
-- 20110517, modifications pour la gestion des présences dans les eps
-- *****************************************************************************
SELECT add_missing_table_field ('public', 'commissionseps_membreseps', 'suppleant', 'INTEGER');
ALTER TABLE commissionseps_membreseps DROP COLUMN suppleant;
SELECT add_missing_table_field ('public', 'commissionseps_membreseps', 'reponsesuppleant_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'commissionseps_membreseps', 'commissionseps_membreseps_reponsesuppleant_id_fkey', 'membreseps', 'reponsesuppleant_id');
SELECT add_missing_table_field ('public', 'commissionseps_membreseps', 'presencesuppleant_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'commissionseps_membreseps', 'commissionseps_membreseps_presencesuppleant_id_fkey', 'membreseps', 'presencesuppleant_id');

SELECT add_missing_table_field ('public', 'commissionseps_membreseps', 'suppleant_id', 'INTEGER');
ALTER TABLE commissionseps_membreseps DROP COLUMN suppleant_id;

-- *****************************************************************************
-- 20110517, ajout du champ pour le bouton radio du cg93
-- *****************************************************************************
DROP TYPE IF EXISTS TYPE_DECISIONPCG;
CREATE TYPE TYPE_DECISIONPCG AS ENUM ( 'valide', 'enattente' );

SELECT add_missing_table_field ('public', 'decisionsnonrespectssanctionseps93', 'decisionpcg', 'TYPE_DECISIONPCG');
ALTER TABLE decisionsnonrespectssanctionseps93 ALTER COLUMN decisionpcg SET DEFAULT 'valide'::type_decisionpcg;
SELECT add_missing_table_field ('public', 'decisionsreorientationseps93', 'decisionpcg', 'TYPE_DECISIONPCG');
ALTER TABLE decisionsreorientationseps93 ALTER COLUMN decisionpcg SET DEFAULT 'valide'::type_decisionpcg;
SELECT add_missing_table_field ('public', 'decisionsnonorientationsproseps93', 'decisionpcg', 'TYPE_DECISIONPCG');
ALTER TABLE decisionsnonorientationsproseps93 ALTER COLUMN decisionpcg SET DEFAULT 'valide'::type_decisionpcg;
SELECT add_missing_table_field ('public', 'decisionsregressionsorientationseps93', 'decisionpcg', 'TYPE_DECISIONPCG');
ALTER TABLE decisionsregressionsorientationseps93 ALTER COLUMN decisionpcg SET DEFAULT 'valide'::type_decisionpcg;
SELECT add_missing_table_field ('public', 'decisionssignalementseps93', 'decisionpcg', 'TYPE_DECISIONPCG');
ALTER TABLE decisionssignalementseps93 ALTER COLUMN decisionpcg SET DEFAULT 'valide'::type_decisionpcg;
SELECT add_missing_table_field ('public', 'decisionscontratscomplexeseps93', 'decisionpcg', 'TYPE_DECISIONPCG');
ALTER TABLE decisionscontratscomplexeseps93 ALTER COLUMN decisionpcg SET DEFAULT 'valide'::type_decisionpcg;

-- *****************************************************************************
-- 20110523, ajout de champs de commentaires dans les décisions du module EP
-- pour les thématiques où elles manquent
-- *****************************************************************************

SELECT add_missing_table_field ('public', 'decisionscontratscomplexeseps93', 'commentaire', 'TEXT');
SELECT add_missing_table_field ('public', 'defautsinsertionseps66', 'commentaire', 'TEXT');


-- *******************************************************************************************************
-- 20110524, ajout de la valeur Absent pour les présences des participants à une commission EP
-- *******************************************************************************************************
ALTER TABLE commissionseps_membreseps ALTER COLUMN presence TYPE TEXT;
DROP TYPE TYPE_PRESENCESEANCEEP;
CREATE TYPE TYPE_PRESENCESEANCEEP AS ENUM ( 'present', 'excuse', 'absent', 'remplacepar' );
ALTER TABLE commissionseps_membreseps ALTER COLUMN presence TYPE TYPE_PRESENCESEANCEEP USING CAST(presence AS TYPE_PRESENCESEANCEEP);

-- *******************************************************************************************************
-- 20110524, ajout d'un état à la commission pour la figée avant de prendre les présences
-- *******************************************************************************************************
ALTER TABLE commissionseps ALTER COLUMN etatcommissionep TYPE TEXT;
ALTER TABLE commissionseps ALTER COLUMN etatcommissionep DROP DEFAULT;
DROP TYPE IF EXISTS TYPE_ETATCOMMISSIONEP;
CREATE TYPE TYPE_ETATCOMMISSIONEP AS ENUM ( 'cree', 'associe', 'valide', 'presence', 'decisionep', 'traiteep', 'decisioncg', 'traite', 'annule', 'reporte' );
ALTER TABLE commissionseps ALTER COLUMN etatcommissionep TYPE TYPE_ETATCOMMISSIONEP USING CAST(etatcommissionep AS TYPE_ETATCOMMISSIONEP);
ALTER TABLE commissionseps ALTER COLUMN etatcommissionep SET DEFAULT 'cree'::TYPE_ETATCOMMISSIONEP;

-- *******************************************************************************************************
-- 20110524, ajout de l'organisme aux membres de l'EP
-- *******************************************************************************************************

SELECT add_missing_table_field ('public', 'membreseps', 'organisme', 'VARCHAR(250)');
ALTER TABLE membreseps ALTER COLUMN organisme SET DEFAULT NULL;

-- *******************************************************************************************************
-- 20110524, ajout de la date d'impression de la décision d'un passage en EP
-- *******************************************************************************************************

SELECT add_missing_table_field ('public', 'passagescommissionseps', 'impressiondecision', 'DATE');
ALTER TABLE passagescommissionseps ALTER COLUMN impressiondecision SET DEFAULT NULL;

-- *******************************************************************************************************
-- 20110525, ajout d'un champ pour la détection du statut de rdv qui va permettre en passage en
-- epl audition
-- *******************************************************************************************************
SELECT add_missing_table_field ('public', 'statutsrdvs', 'permetpassageepl', 'TYPE_BOOLEANNUMBER');
ALTER TABLE statutsrdvs ALTER COLUMN permetpassageepl SET DEFAULT '0';
UPDATE statutsrdvs SET permetpassageepl = '0' WHERE permetpassageepl IS NULL;
ALTER TABLE statutsrdvs ALTER COLUMN permetpassageepl SET NOT NULL;

-- *******************************************************************************************************
-- 20110525:
--   * suppression des décision de la table nonrespectssanctionseps93
--   * suppression des valeurs 1sursis et 2report des décisions des decisionsnonrespectssanctionseps93
--   * suppression des valeurs 1sursis et 2report des décisions des decisionssignalementseps93
-- *******************************************************************************************************

ALTER TABLE nonrespectssanctionseps93 DROP COLUMN decision;
ALTER TABLE nonrespectssanctionseps93 DROP COLUMN montantreduction;
ALTER TABLE nonrespectssanctionseps93 DROP COLUMN dureesursis;

-- -------------------------------------------------------------------------------------------------------

UPDATE decisionsnonrespectssanctionseps93 SET decision = '1delai'::TYPE_DECISIONSANCTIONEP93 WHERE decision = '1sursis'::TYPE_DECISIONSANCTIONEP93;

ALTER TABLE decisionsnonrespectssanctionseps93 ALTER COLUMN decision TYPE TEXT;
ALTER TABLE decisionsnonrespectssanctionseps93 ALTER COLUMN decision DROP DEFAULT;
DROP TYPE IF EXISTS TYPE_DECISIONSANCTIONEP93;
CREATE TYPE TYPE_DECISIONSANCTIONEP93 AS ENUM ( '1reduction', '1maintien', '1pasavis', '1delai', '2suspensiontotale', '2suspensionpartielle', '2maintien', '2pasavis', 'annule', 'reporte' );
ALTER TABLE decisionsnonrespectssanctionseps93 ALTER COLUMN decision TYPE TYPE_DECISIONSANCTIONEP93 USING CAST(decision AS TYPE_DECISIONSANCTIONEP93);

-- -------------------------------------------------------------------------------------------------------

UPDATE decisionssignalementseps93 SET decision = '1delai'::TYPE_DECISIONSIGNALEMENTEP93 WHERE decision = '1sursis'::TYPE_DECISIONSIGNALEMENTEP93;

ALTER TABLE decisionssignalementseps93 ALTER COLUMN decision TYPE TEXT;
ALTER TABLE decisionssignalementseps93 ALTER COLUMN decision DROP DEFAULT;
DROP TYPE IF EXISTS TYPE_DECISIONSIGNALEMENTEP93;
CREATE TYPE TYPE_DECISIONSIGNALEMENTEP93 AS ENUM ( '1reduction', '1maintien', '1pasavis', '1delai', '2suspensiontotale', '2suspensionpartielle', '2maintien', '2pasavis', 'annule', 'reporte' );
ALTER TABLE decisionssignalementseps93 ALTER COLUMN decision TYPE TYPE_DECISIONSIGNALEMENTEP93 USING CAST(decision AS TYPE_DECISIONSIGNALEMENTEP93);

-- *******************************************************************************************************
-- 20110525, ajout de la date d'impression de la convocation de l'allocataire
-- *******************************************************************************************************

SELECT add_missing_table_field ('public', 'passagescommissionseps', 'impressionconvocation', 'DATE');
ALTER TABLE passagescommissionseps ALTER COLUMN impressionconvocation SET DEFAULT NULL;


-- *******************************************************************************************************
-- 20110525, ajout des champs created et modified dans la table rendezvous
-- *******************************************************************************************************

SELECT add_missing_table_field ('public', 'rendezvous', 'created', 'TIMESTAMP WITHOUT TIME ZONE');
ALTER TABLE rendezvous ALTER COLUMN created SET DEFAULT NULL;
SELECT add_missing_table_field ('public', 'rendezvous', 'modified', 'TIMESTAMP WITHOUT TIME ZONE');
ALTER TABLE rendezvous ALTER COLUMN modified SET DEFAULT NULL;

-- *******************************************************************************************************
-- 20110526, mise à jour des dates d'impression des convocations et des décsions des passages en EP.
-- *******************************************************************************************************

UPDATE passagescommissionseps
	SET impressionconvocation = (
		SELECT date_trunc( 'day', commissionseps.dateseance ) - INTERVAL '31 days'
			FROM commissionseps
			WHERE commissionseps.id = passagescommissionseps.commissionep_id
		)
	WHERE
		impressionconvocation IS NULL;

UPDATE passagescommissionseps
	SET impressiondecision = (
		SELECT date_trunc( 'day', commissionseps.dateseance )
			FROM commissionseps
			WHERE commissionseps.id = passagescommissionseps.commissionep_id
		)
	WHERE
		impressiondecision IS NULL
		AND etatdossierep IN ( 'traite', 'annule', 'reporte' );

-- *******************************************************************************************************
-- 20110531: nettoyage des tables des décisions d'EP
-- *******************************************************************************************************

-- Suppression du caractère obligatoire du champ decision des tables de décision
ALTER TABLE decisionscontratscomplexeseps93 ALTER COLUMN decision DROP NOT NULL;
ALTER TABLE decisionscontratscomplexeseps93 ALTER COLUMN decision SET DEFAULT NULL;

ALTER TABLE decisionsdefautsinsertionseps66 ALTER COLUMN decision DROP NOT NULL;
ALTER TABLE decisionsdefautsinsertionseps66 ALTER COLUMN decision SET DEFAULT NULL;

ALTER TABLE decisionsregressionsorientationseps58 ALTER COLUMN decision DROP NOT NULL;
ALTER TABLE decisionsregressionsorientationseps58 ALTER COLUMN decision SET DEFAULT NULL;

ALTER TABLE decisionsreorientationseps93 ALTER COLUMN decision DROP NOT NULL;
ALTER TABLE decisionsreorientationseps93 ALTER COLUMN decision SET DEFAULT NULL;

ALTER TABLE decisionssanctionsrendezvouseps58 ALTER COLUMN decision DROP NOT NULL;
ALTER TABLE decisionssanctionsrendezvouseps58 ALTER COLUMN decision SET DEFAULT NULL;

ALTER TABLE decisionssignalementseps93 ALTER COLUMN decision DROP NOT NULL;
ALTER TABLE decisionssignalementseps93 ALTER COLUMN decision SET DEFAULT NULL;

-- Ajout du caractère obligatoire du champ passagecommissionep_id des tables de décisions
ALTER TABLE decisionsdefautsinsertionseps66 ALTER COLUMN passagecommissionep_id SET NOT NULL;
ALTER TABLE decisionsregressionsorientationseps58 ALTER COLUMN passagecommissionep_id SET NOT NULL;
ALTER TABLE decisionsreorientationseps93 ALTER COLUMN passagecommissionep_id SET NOT NULL;
ALTER TABLE decisionsnonorientationsproseps58 ALTER COLUMN passagecommissionep_id SET NOT NULL;
ALTER TABLE decisionsnonorientationsproseps93 ALTER COLUMN passagecommissionep_id SET NOT NULL;
ALTER TABLE decisionsnonrespectssanctionseps93 ALTER COLUMN passagecommissionep_id SET NOT NULL;
ALTER TABLE decisionssaisinespdoseps66 ALTER COLUMN passagecommissionep_id SET NOT NULL;
ALTER TABLE decisionssaisinesbilansparcourseps66 ALTER COLUMN passagecommissionep_id SET NOT NULL;
ALTER TABLE decisionssanctionseps58 ALTER COLUMN passagecommissionep_id SET NOT NULL;

-- Il manque le champ passagecommissionep_id dans la table decisionsregressionsorientationseps93
DELETE FROM decisionsregressionsorientationseps93;
ALTER TABLE decisionsregressionsorientationseps93 ADD COLUMN passagecommissionep_id INTEGER DEFAULT NULL REFERENCES passagescommissionseps(id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE decisionsregressionsorientationseps93 ALTER COLUMN passagecommissionep_id SET NOT NULL;

-- Le champ regressionorientationep93_id de la table decisionsregressionsorientationseps93 ne sert à rien
ALTER TABLE decisionsregressionsorientationseps93 DROP COLUMN regressionorientationep93_id;

-- *******************************************************************************************************
-- 20110531: ces thématiques ne sont pas utilsées actuellement
-- FIXME: supprimer les fichiers de modèle
-- *******************************************************************************************************

DROP TABLE decisionsnonorientationsproseps66;
DROP TABLE decisionsregressionsorientationseps93;

DROP TABLE nonorientationsproseps66;
DROP TABLE regressionsorientationseps93;

ALTER TABLE regroupementseps DROP COLUMN regressionorientationep93;

-- *******************************************************************************************************
-- 20110601: suppression de la règle not null car ajout de la règle de validation dans le modèle
-- *******************************************************************************************************

ALTER TABLE actionscandidats ALTER COLUMN nbpostedispo DROP NOT NULL;
ALTER TABLE actionscandidats ALTER COLUMN nbpostedispo SET DEFAULT NULL;

-- *******************************************************************************************************
-- 20110601: ajout de la notion de corum pour le cg66
-- *******************************************************************************************************

SELECT add_missing_table_field ('public', 'regroupementseps', 'nbminmembre', 'INTEGER');
UPDATE regroupementseps SET nbminmembre = 0 WHERE nbminmembre IS NULL;
ALTER TABLE regroupementseps ALTER COLUMN nbminmembre SET DEFAULT 0;
ALTER TABLE regroupementseps ALTER COLUMN nbminmembre SET NOT NULL;
SELECT add_missing_table_field ('public', 'regroupementseps', 'nbmaxmembre', 'INTEGER');
UPDATE regroupementseps SET nbmaxmembre = 0 WHERE nbmaxmembre IS NULL;
ALTER TABLE regroupementseps ALTER COLUMN nbmaxmembre SET DEFAULT 0;
ALTER TABLE regroupementseps ALTER COLUMN nbmaxmembre SET NOT NULL;

DROP TABLE IF EXISTS compositionsregroupementseps;
CREATE TABLE compositionsregroupementseps (
	id      				SERIAL NOT NULL PRIMARY KEY,
	fonctionmembreep_id		INTEGER NOT NULL REFERENCES fonctionsmembreseps(id) ON DELETE CASCADE ON UPDATE CASCADE,
	regroupementep_id		INTEGER NOT NULL REFERENCES regroupementseps(id) ON DELETE CASCADE ON UPDATE CASCADE,
	prioritaire				TYPE_BOOLEANNUMBER NOT NULL DEFAULT '0'::TYPE_BOOLEANNUMBER,
	obligatoire				TYPE_BOOLEANNUMBER NOT NULL DEFAULT '0'::TYPE_BOOLEANNUMBER
);
COMMENT ON TABLE compositionsregroupementseps IS 'Composition des EPs';


TRUNCATE TABLE actionscandidats CASCADE;
SELECT add_missing_table_field ( 'public', 'actionscandidats', 'contactpartenaire_id', 'INTEGER' );
ALTER TABLE actionscandidats ALTER COLUMN contactpartenaire_id SET NOT NULL;
ALTER TABLE actionscandidats ADD CONSTRAINT actionscandidats_contactpartenaire_id_fk FOREIGN KEY (contactpartenaire_id) REFERENCES contactspartenaires(id);
-- SELECT alter_table_drop_column_if_exists ( 'public', 'contactspartenaires', 'partenaire_id' );

SELECT add_missing_table_field ( 'public', 'contactspartenaires', 'numfax', 'VARCHAR(10)' );

ALTER TABLE actionscandidats_personnes ALTER COLUMN positionfiche TYPE TEXT;
ALTER TABLE actionscandidats_personnes ALTER COLUMN positionfiche DROP DEFAULT;
DROP TYPE IF EXISTS TYPE_POSITIONFICHE;
CREATE TYPE TYPE_POSITIONFICHE AS ENUM ( 'enattente', 'encours', 'nonretenue', 'sortie', 'annule' );
ALTER TABLE actionscandidats_personnes ALTER COLUMN positionfiche TYPE TYPE_POSITIONFICHE USING CAST(positionfiche AS TYPE_POSITIONFICHE);

-- *******************************************************************************************************
-- 20110601: mise en place de l'état quorum pour le cg66
-- *******************************************************************************************************

ALTER TABLE commissionseps ALTER COLUMN etatcommissionep TYPE TEXT;
ALTER TABLE commissionseps ALTER COLUMN etatcommissionep DROP DEFAULT;
DROP TYPE IF EXISTS TYPE_ETATCOMMISSIONEP;
CREATE TYPE TYPE_ETATCOMMISSIONEP AS ENUM ( 'cree', 'quorum', 'associe', 'valide', 'presence', 'decisionep', 'traiteep', 'decisioncg', 'traite', 'annule', 'reporte' );
ALTER TABLE commissionseps ALTER COLUMN etatcommissionep TYPE TYPE_ETATCOMMISSIONEP USING CAST(etatcommissionep AS TYPE_ETATCOMMISSIONEP);
ALTER TABLE commissionseps ALTER COLUMN etatcommissionep SET DEFAULT 'cree'::TYPE_ETATCOMMISSIONEP;



-- -----------------------------------------------------------------------------------------------
-- 20110607: Ajout d'un champ pour sélectionner si on ajoute des fichiers ou non aux Fiches de candidatures / de liaison
-- -----------------------------------------------------------------------------------------------
SELECT add_missing_table_field ('public', 'actionscandidats_personnes', 'haspiecejointe', 'type_booleannumber');
ALTER TABLE actionscandidats_personnes ALTER COLUMN haspiecejointe SET DEFAULT '0'::TYPE_BOOLEANNUMBER;
UPDATE actionscandidats_personnes SET haspiecejointe = '0'::TYPE_BOOLEANNUMBER WHERE haspiecejointe IS NULL;
ALTER TABLE actionscandidats_personnes ALTER COLUMN haspiecejointe SET NOT NULL;

ALTER TABLE partenaires ALTER COLUMN compladr DROP NOT NULL;
ALTER TABLE partenaires ALTER COLUMN compladr SET DEFAULT NULL;
-- *****************************************************************************
COMMIT;
-- *****************************************************************************
